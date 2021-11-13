<?php
require('dbconnect.php');
session_start();
require('index_php.php');
require('functions.php');
?>

<!DOCTYPE html>
<html>
<head>
<style>
.sentence {
  color:white;
}

.tytle{
  background: white;
  color:#00BFFF;
  font-size:40px;
  font-family:'メイリオ';
}

.error_messege{color:#990000;}

.message{background-color: white;}
.time{font-size:10px;}
.reaction a{
  text-decoration: none;
}
body {
  background-color:#00BFFF;
}

</style>
</head>

<div class="tytle"><tytle>&nbsp;投稿</tytle></div>
<body>
<a href="index.php">ホーム</a>
<?php $posts_replys = $db -> prepare('SELECT count(*) AS reply_count_number FROM posts WHERE reply_id=?');
      $posts_replys -> execute(array($_REQUEST['id']));
      $posts_reply = $posts_replys -> fetch();

?>
<?php if($posts_reply['reply_count_number']>1 || $posts_reply['reply_count_number']==0):?>
  <div>
  <?php
   $posts = $db -> prepare('SELECT p.*, m.name, m.picture FROM posts p, members m
    WHERE p.member_id = m.id AND (p.id=? || p.reply_id=?) ORDER BY p.id=? DESC ,created DESC');
   $posts -> execute(array($_REQUEST['id'], $_REQUEST['id'], $_REQUEST['id']));?>
   <div class='message'>
   <hr>
   <?php foreach($posts as $post): ?>
    <?php require('div_message.php');?>
   <?php endforeach; ?>
   </div>

<?php else:?>

 <?php
 $switch=0;
 $res=$_REQUEST['id'];
  ?>
  <div class='message'>
  <hr>
 <?php while($res):?>
 <?php
   if($switch==1){
     $posts1 = $db -> prepare('SELECT id FROM posts WHERE reply_id=?');
     $posts1 -> execute(array($res));
     $post1 = $posts1 -> fetch();
     if(!$post1){break;}
     $posts = $db -> prepare('SELECT p.*, m.name, m.picture FROM posts p, members m
       WHERE p.member_id = m.id AND p.id=?');
     $posts -> execute(array($post1['id']));
     $post = $posts ->fetch();
   }
   else{
     $posts = $db -> prepare('SELECT p.*, m.name, m.picture FROM posts p, members m
       WHERE p.member_id = m.id AND p.id=?');
     $posts -> execute(array($res));
     $post = $posts ->fetch();
     $switch=1;
   }

   require('div_message.php');
   $res=$post['id'];
?>
<?php endwhile; ?>
</div>
<?php endif;?>

</body>
</html>
