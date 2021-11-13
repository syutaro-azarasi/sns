  <style>
  .comment_member img{
    margin-top:5px;
    margin-left:5px;
  }
  .reaction a{
    text-decoration:none;
    color:black;
  }
  .reaction img{
    vertical-align: middle;
  }
  .time{font-size:10pt;}
  </style>
  <?php $like_records = $db -> prepare('SELECT * FROM favorite WHERE message_id=? AND favorite_member=?');
        $like_records-> execute(array($post['id'], $member['id']));
        $like_record = $like_records -> fetch();
  ?>

  <div class="comment_member">
  <a style="text-decoration:none;"
    href="profile.php?id=<?php echo htmlspecialchars($post['member_id'], ENT_QUOTES);?>">
  <p><img style="vertical-align: middle; border-radius:20px;"
    src="../join/member_picuture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES)?>"
    width="40" height="40"/>
  </a>
    <?php echo htmlspecialchars($post['name'], ENT_QUOTES);?>
    <time class="time"><?php print($post['created'])?></time>
  </p>
  </div>

  <span><a style="text-decoration:none;"
   href="profile.php?id=<?php echo $post['replied_member_id'];?>">
    <?php echo htmlspecialchars($post['reply_name'], ENT_QUOTES)?>
  </a></span>
  <span style="word-wrap:break-all;"><?php echo makeLink(htmlspecialchars($post['message']));?></span><br>
  <?php $ext=substr($post['filename'],-4);?>
  <div>
  <?php if($ext == 'jpeg' || $ext == '.png' || $ext == '.gif'):?>
　  <img src="message_file/<?php echo htmlspecialchars($post['filename'], ENT_QUOTES) ?>"
    height="200">
  <?php endif;?>
  </div>
  <div>
  <?php if($ext == '.mp4' || $ext == '.mov' || $ext == '.avi'): ?>
    <video src="message_file/<?php echo htmlspecialchars($post['filename'], ENT_QUOTES)?>"
        width=200, height="=200" controls>
  <?php endif;?>
  </div>
   &nbsp;

  <div class="reaction">
   &nbsp;<span>
     <a href="index.php?like=<?php echo h($post['id']);?>&liked_member=<?php echo h($post['member_id']); ?>"/>
      <img src="dezain_picture/heart_<?php if($like_record){echo 'red';}else{echo 'mono';}?>.png"
       width=20, height="20">
     </a></span>
   <?php if($post['like_count']!=0){
    echo $post['like_count'];
   }?>
   &nbsp;&nbsp;
   <span><a
     href="javascript:win_open('index_message.php?res=<?php echo h($post['id']);?>');">
     <img src="dezain_picture/message.png"
      width=20, height="20">
   </a></span>
   <?php if($post['reply_count']!=0){
    echo $post['reply_count'];
   }?>
   &nbsp;&nbsp;
   <?php if($post['member_id']==$_SESSION['id']):?>
   <span><a href="index.php?del=<?php echo htmlspecialchars($post['id'], ENT_QUOTES);?>">
     <img src="dezain_picture/delete.png"
      width=20, height="20">
   </a></sapn>
   <?php endif;?>
   &nbsp;&nbsp;
   <a style="color:black;"
    href="message.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES);?>"> >
   </a>
 </div>
   <hr>
