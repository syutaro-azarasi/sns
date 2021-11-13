<?php
session_start();
require('dbconnect.php');
$members = $db -> prepare('SELECT * FROM members WHERE id=?');
$members->execute(array($_SESSION['id']));
$member = $members -> fetch();
require('index_php.php');
require('functions.php');
require('menu.php');
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
