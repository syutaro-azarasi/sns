<?php
session_start();
require('dbconnect.php');
$members = $db -> prepare('SELECT * FROM members WHERE id=?');　
$members->execute(array($_SESSION['id']));/*セッションに保存されたIDでアカウント情報を取得*/
$member = $members -> fetch();
require('functions.php');
require('menu.php');/*メニューバーのコードファイル*/

if(isset($_SESSION['id'])) {
  $posts = $db -> query('SELECT p.*, m.name, m.picture FROM posts p, members m
   WHERE p.member_id = m.id ORDER BY created DESC');
 }
 else {
   header('Location:../join/login.php');
 }
if(isset($_POST['message']))  {

  $fileName = $_FILES['filename']['name'];

  if(!empty($fileName)) {
    $ext = substr($fileName,-4);
    if ($ext == 'jpeg' || $ext == '.png' || $ext == '.gif') {
      $image = 'image' . date('YmdHis') . $_FILES['filename']['name'];
      move_uploaded_file($_FILES['filename']['tmp_name'], './message_file/'.$image);
    }
    if ($ext == '.mp4' || $ext == '.mov' || $ext == '.avi') {
      $image = 'movie' . date('YmdHis') . $_FILES['filename']['name'];
      move_uploaded_file($_FILES['filename']['tmp_name'], './message_file/'.$image);
    }
  }

  if(isset($_REQUEST['res'])){
    $reply_counts1 = $db
    -> prepare('SELECT p.reply_count, m.name, m.id FROM posts p, members m WHERE p.id=? AND p.member_id=m.id');
    $reply_counts1 -> execute(array($_REQUEST['res']));
    $reply_count1 = $reply_counts1 -> fetch();
    $reply_counts2 = $db -> prepare('UPDATE posts SET reply_count=? WHERE id=?');
    $reply_counts2 -> execute(array($reply_count1['reply_count']+1, $_REQUEST['res']));
    $notice_reply = $db -> prepare('UPDATE members SET
      notice_reply_count=notice_reply_count+1 WHERE id=?');
    $notice_reply -> execute(array($reply_count1['id']));
    $reply=$_REQUEST['res'];
    $reply_name='@'.$reply_count1['name'];
    $replied_member_id=$reply_count1['id'];
  }
  if(empty($_REQUEST['res'])){
    $reply=0;
    $reply_name='';
    $replied_member_id=0;
  }
      $statement = $db -> prepare('INSERT INTO posts SET
        member_id=?,
        message=?,
        reply_id=?,
        reply_name=?,
        replied_member_id=?,
        filename=?,
        created=NOW()');
      $statement -> execute(array(
        $_SESSION['id'],
        $_POST['message'],
        $reply,
        $reply_name,
        $replied_member_id,
        $image));
      $end='T';
  }

if(isset($_REQUEST['like'])){
  $like_records = $db -> prepare('SELECT id FROM favorite WHERE message_id=? AND favorite_member=?');
  $like_records-> execute(array($_REQUEST['like'], $_SESSION['id']));
  $like_record = $like_records-> fetch();

  if($like_record){
    $like_counts1 = $db -> prepare('SELECT like_count FROM posts WHERE id=?');
    $like_counts1 -> execute(array($_REQUEST['like']));
    $like_count1 = $like_counts1 -> fetch();
    $like_counts2 = $db -> prepare('UPDATE posts SET like_count=? WHERE id=?');
    $like_counts2 -> execute(array($like_count1['like_count']-1, $_REQUEST['like']));
    $like_delete = $db -> prepare('DELETE FROM favorite WHERE message_id=? AND favorite_member=?');
    $like_delete -> execute(array($_REQUEST['like'], $_SESSION['id']));
    header('Location:index.php');
    exit();
  }
  else{
    $like_counts1 = $db -> prepare('SELECT like_count FROM posts WHERE id=?');
    $like_counts1 -> execute(array($_REQUEST['like']));
    $like_count1 = $like_counts1 -> fetch();
    $like_counts2 = $db -> prepare('UPDATE posts SET like_count=? WHERE id=?');
    $like_counts2 -> execute(array($like_count1['like_count']+1, $_REQUEST['like']));
    $like = $db -> prepare('INSERT INTO favorite SET
     liked_member=?, message_id=?, favorite_member=?, created=NOW()');
    $like -> execute(array($_REQUEST['liked_member'], $_REQUEST['like'], $_SESSION['id']));
    $notice_like = $db -> prepare('UPDATE members SET
      notice_like_count=notice_like_count+1 WHERE id=?');
    $notice_like -> execute(array($_REQUEST['liked_member']));
    header('Location:index.php');
    exit();
  }
}

if(isset($_REQUEST['res'])) {
  $response = $db -> prepare('SELECT m.name, m.picture, p.* FROM members m,
   posts p WHERE m.id=p.member_id AND p.id=?');
  $response -> execute(array($_REQUEST['res']));
  $table = $response->fetch();
  $message = '[@'.$table['name'].' '.$table['message'].']への返信';
}

if(isset($_REQUEST['del'])) {
  $delete_counts1= $db -> prepare('SELECT * FROM posts WHERE id=?');
  $delete_counts1 -> execute(array($_REQUEST['del']));
  $delete_count1= $delete_counts1 -> fetch();
  if($delete_count1['filename']){
    unlink("message_file/".$delete_count1['filename']);
  }
  if($delete_count1['reply_id']!=0)  {
    $delete_counts2= $db -> prepare('SELECT * FROM posts WHERE id=?');
    $delete_counts2 -> execute(array($delete_count1['reply_id']));
    $delete_count2 = $delete_counts2 ->fetch();

    $delete_counts3= $db -> prepare('UPDATE posts SET reply_count=? WHERE id=?');
    $delete_counts3 -> execute(array($delete_count2['reply_count']-1, $delete_count1['reply_id']));

  }
  $delete = $db -> prepare('DELETE FROM posts WHERE id=?');
  $delete->execute(array($_REQUEST['del']));

  header('Location:index.php');
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
<style>
.sentence {
  color:white;
}

.notice{
 display:inline-block;
 position:relative;
 width:30px;
 height:30px;
}
.notice img{
 width:30px;
 height:30px;
}
.menu{text-align:center;
  position:fixed;top:100px;height:100%;width:5%;
  background-color:white;}
.notice_count{
  position: absolute;
  text-align: center;
  bottom:0px;
  right:-10px;
  width: 20px;
  border-radius:50%;
  background-color:#FF367F;z-index:30;
}
.message_block{margin-top:100px;}
.message{background-color:white;width:90%;margin-left:8%;
overflow:hidden;}

.time{font-size:10px;}

.bottun_tytle{
  text-decoration:none;
  color:#00BFFF;
  width:20px;
  height:1%;
  font-size:10px;
  font-family:'メイリオ';
}
.button{
 border:transparent;
 background-color:transparent;
 position:fixed;
 top:80%;
 right:5%;
}
.button img{
 width:100px;height:100px;
}

</style>
</head>

<body>
<button class="button">
  <a class="bottun_tytle" href="javascript:win_open('index_message.php','width=300px,height=500px');">
   <img src="dezain_picture/message_input.png">
  </a>
</button><br>
<div class="menu">
 &nbsp;<br>
 <a href="notice_like.php?id=<?php echo h($member['id']);?>">
   <div class="notice">
      <img src="dezain_picture/heart_notice.png">
     <p class="notice_count">
       <?php if($member['notice_like_count']!=0){echo h($member['notice_like_count']);}?>
     </p>
   </div>
 </a><br>
 &nbsp;<br>
 <a href="notice_reply.php?id=<?php echo h($member['id']);?>">
  <div class="notice">
    <img src="dezain_picture/message_notice.png">
    <p class="notice_count">
     <?php if($member['notice_reply_count']!=0){echo h($member['notice_reply_count']);}?>
    </p>
  </div>
 </a><br>&nbsp;
</div>
<div class="message_block">

  <?php $posts = $db -> query('SELECT p.*, m.name, m.picture
     FROM posts p, members m
     WHERE p.member_id = m.id ORDER BY created DESC');?>

<?php foreach($posts as $post): ?>
 <?php
   $follows = $db -> prepare('SELECT * FROM follow
     WHERE follower=? AND follow=?');
   $follows -> execute(array($member['id'], $post['member_id']));
   $follow = $follows -> fetch();
 ?>
<?php if(($follow['follow']==$post['member_id'])|| ($_SESSION['id']==$post['member_id']) ):?>
<div class='message'>
  <?php require('div_message.php');?>
</div>
<?php endif;?>
<?php endforeach; ?>
</div>


</body>
</html>
