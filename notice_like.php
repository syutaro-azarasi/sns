<?php
require('dbconnect.php');
session_start();
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
.menu{
  text-align:center;
  position:fixed;top:100px;height:100%;width:5%;
  background-color:white;
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
.notice_block{
  margin-top:100px;
  margin-left: 8%;
}
.error_messege{color:#990000;}

.message{background-color: white;}
.time{font-size:10px;}
.reaction a{
  text-decoration: none;
}


</style>
</head>


<body>
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
  <?php
  if($_REQUEST['id']){
   $delete_notice = $db -> prepare('UPDATE members SET notice_like_count=0 WHERE id=?');
   $delete_notice -> execute(array($_REQUEST['id']));
  }
  ?>
<a href="index.php">ホーム</a>
<div class='message'>
<hr>
<?php
$posts = $db
-> prepare('SELECT m.name, m.picture, f.message_id, p.id, p.message, p.reply_name
  FROM members m, favorite f LEFT JOIN posts p ON f.message_id=p.id
  WHERE m.id=f.favorite_member AND f.liked_member=? ORDER BY f.created DESC');
$posts -> execute(array($_REQUEST['id']));
?>
<div class="notice_block">
 <?php foreach($posts as $post): ?>
 <img src="../join/member_picuture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES)?>"
 width=30 height="30"/>
 <span><?php echo htmlspecialchars($post['name'], ENT_QUOTES)?>がいいねをしました</span><br>
 &nbsp;<br>
 <span><?php echo htmlspecialchars($post['reply_name'], ENT_QUOTES)?>
       <?php echo htmlspecialchars($post['message'], ENT_QUOTES)?>
 </span>
 &nbsp;&nbsp;&nbsp;
 <a style="color:black;text-decoration: none;"
  href="message.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES);?>"> > </a>
 <hr>
 <?php endforeach; ?>
</div>

</body>
</html>
