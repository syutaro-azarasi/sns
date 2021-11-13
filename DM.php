<?php
 require('dbconnect.php');
 session_start();
 require('functions.php');

 if($_REQUEST['flag']==T){
  $DM_request = $db -> prepare('UPDATE DM_request
    SET answer=TRUE, room_id=? WHERE request_member=? AND requested_member=?');
  $DM_request -> execute(array($_SESSION['id'].date('YmdHis').$_REQUEST['id'],
  $_REQUEST['id'],$_SESSION['id']));
  header("Location:DM.php");
  exit();
 }
 if(!empty($_POST['DM'])){
  $DM_message = $db -> prepare('INSERT INTO DM_message
    SET my_id=?, message=?, room_id=?, created=NOW()');
  $DM_message
    ->execute(array($_SESSION['id'],$_POST['DM'],$_REQUEST['room_id']));
  $_SESSION['scroll']=$_POST['scroll_top'];
  header("Location:DM.php?room_id=".$_REQUEST['room_id'].'&id='.$_REQUEST['id']);
  exit();
 }
 ?>
<!DOCTYPE html>
<html>
<head>
 <style>
  .request_list{background-color:white;border:1px solid;border-left: none;
    width:100%;height:80px;
    position:fixed;z-index:5;margin-top:60px;padding-top:15px;padding-bottom:15px;
  }
  .request_member{
    margin-left:2%;
    display:inline-block;
    text-align:center;
    }
  .request_member img{
    display:inline-block;
    border:0.5px solid black;
    border-radius:50%;
    }
  .DM_history_list{background-color:white;width:40%;height:100%;
    position:fixed;top:200px;position:fixed;z-index:5;
    border-right:1px solid;
    font-size:20pt;
    }
  .DM_history{position:relative;left:5px;
    padding:10px 10px;
    width:95%;border-bottom:1px solid;}
  .DM_history img{border-radius:50%;vertical-align:middle;border:0.5px solid black;}
  .DM_history time{font-size:10pt;}
  #DM_header{position:fixed;top:80px;right:10px;text-align:center;
    width:800px;height:60px;padding:5px 10px;box-shadow:1px 1px 2px;
    background-color:white;z-index:30;}
  #DM_header img{border-radius:50%;vertical-align:middle;border: solid 0.5px;}
  #DM_header span{font-size:30px;}
  #DM_window{position:fixed;top:150px;right:10px;
    background-color:#EEFFFF;box-shadow:1px 1px 2px;
    width:800px;height:550px;overflow:scroll;z-index:20;padding:0px 10px;}
  #DM_window img{vertical-align: middle;}
  #DM_input {width:800px;height:100px;border-top:solid 1px;padding:10px 10px;
    position:fixed;top:700px;right:10px;box-shadow:1px 1px 2px;
    text-align:center;background-color:white;z-index:20;}
  #DM_input input.DM{
    width:80%; padding: 5px 8px;
    border-radius: 20px;
    border-top: 1px solid #aaa;
    border-left: 1px solid #aaa;
    border-right: 2px solid #aaa;
    border-bottom: 2px solid #aaa;
    }
 .display{display:block;}
 </style>
 <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
</head>
<body>
 <?php require('menu.php');?>

 <div class="request_list">
  <?php
   $DM_request = $db -> prepare('SELECT D.*, m.id, m.name, m.picture
     FROM DM_request D, members m
     WHERE D.request_member=m.id AND D.requested_member=?');
   $DM_request -> execute(array($_SESSION['id']));
  ?>
  <?php foreach($DM_request as $post):?>
   <?php if(!$post['answer']):?>
    <div class="request_member">
     <img src="../join/member_picuture/<?php echo h($post['picture']);?>"
       width="60" height="60"
       onclick="loca_conf('<?php echo h($post['name']);?>さんのリクエストを受けますか？',
         'DM.php?id=<?php echo h($post['id']);?>&flag=T')"><br>
     <span><?php echo h($post['name']);?></span>
    </div>
   <?php endif;?>
  <?php endforeach;?>
 </div>
 <div class="request_list">
  <?php
   $DM_request = $db -> prepare('SELECT D.*, m.id, m.name, m.picture
     FROM DM_request D, members m
     WHERE D.request_member=m.id AND D.requested_member=?');
   $DM_request -> execute(array($_SESSION['id']));
  ?>
  <?php foreach($DM_request as $post):?>
   <?php if(!$post['answer']):?>
    <div class="request_member">
     <img src="../join/member_picuture/<?php echo h($post['picture']);?>"
       width="60" height="60"
       onclick="loca_conf('<?php echo h($post['name']);?>さんのリクエストを受けますか？',
       'DM.php?id=<?php echo h($post['id']);?>&flag=T')"><br>
     <span><?php echo h($post['name']);?></span>
    </div>
   <?php endif;?>
  <?php endforeach;?>
 </div>
 <div class="DM_history_list">
   <?php
    $DM_request = $db -> prepare('SELECT
      m.id AS id_req,
      m.name AS name_req,
      m.picture AS pic_req,
      m1.id AS id_reqed,
      m1.name AS name_reqed,
      m1.picture AS pic_reqed,
      D.*
      FROM members m, DM_request D
      LEFT JOIN members m1 ON D.requested_member=m1.id
      WHERE (request_member=? || requested_member=?)
      AND m.id=D.request_member
      AND answer=1');
    $DM_request -> execute(array($_SESSION['id'],$_SESSION['id']));
   ?>
   <?php foreach($DM_request as $post):?>
    <?php
     if($post['requested_member']==$_SESSION['id']){
       $post1['picture'] = $post['pic_req'];
       $post1['name'] = $post['name_req'];
       $post1['id'] = $post['id_req'];
     }
     if($post['request_member']==$_SESSION['id']){
       $post1['picture'] = $post['pic_reqed'];
       $post1['name'] = $post['name_reqed'];
       $post1['id'] = $post['id_reqed'];
     }
     $DM_message = $db -> prepare('SELECT d.message, d.created FROM DM_message d
      INNER JOIN (SELECT room_id, MAX(created) AS maxtime FROM DM_message
      WHERE room_id=? GROUP BY room_id) AS maxme ON d.created=maxme.maxtime');
     $DM_message -> execute(array($post['room_id']));
     $DM_message = $DM_message -> fetch();
    ?>
    <div
     onclick="loca('DM.php?room_id=<?php echo h($post['room_id']);?>&id=<?php
     echo h($post1['id']);?>')"
    >
     <img style="vertical-align:top;border: solid 0.5px;border-radius:50%;"
       src="../join/member_picuture/<?php echo h($post1['picture']);?>"
       width="80" height="80">
     <div style="display:inline-block;">
      <span><?php echo h($post1['name']);?></span><br>
      <span style="color:#888888;"><?php
       $strlen = strlen(h($DM_message['message']));
       echo mb_substr(h($DM_message['message']), 0, 15, "UTF-8");
       if($strlen>40){echo'...';}
      ?></span>
     </div>
    </div>
   <hr>
   <?php endforeach;?>
 </div>

 <?php if(!empty($_REQUEST['room_id'])):?>
  <div id="DM_header">
   <?php
    $DM_member = $db -> prepare('SELECT name, picture FROM members WHERE id=?');
    $DM_member -> execute(array($_REQUEST['id']));
    $DM_member = $DM_member -> fetch();
   ?>
   <img src="../join/member_picuture/<?php echo h($DM_member['picture']);?>"
     width="60" height="60">
   <span><?php echo h($DM_member['name']); ?></span>
  </div>
  <div id="DM_window">
   <?php
     $DM_message = $db -> prepare('SELECT m.id, m.name, m.picture, d.*
        FROM members m, DM_message d WHERE m.id=d.my_id AND room_id=?');
     $DM_message -> execute(array($_REQUEST['room_id']));
     foreach($DM_message as $post):
   ?>
  <div style="display:inline-block;width:100%;padding-top:10px;
     text-align:<?php if($post['my_id']==$_SESSION['id']){echo 'right';}?>;">
    <?php if($post['my_id']!=$_SESSION['id']):?>
    <img style="vertical-align:bottom;border: solid 0.5px;border-radius:50%;"
      src="../join/member_picuture/<?php echo h($post['picture']);?>"
      width="30" height="30"><?php endif;?>
    <span style="background-color:white;color:#FF367F;font-size:20px;border-radius:5px;
     <?php if($post['my_id']==$_SESSION['id']){echo 'background-color:#FF367F;color:white;';}?>
     padding:5px 5px;box-shadow:1px 1px 2px;">
     <?php echo h($post['message']);?>
    </span>
  </div><br>&nbsp;<br>
  <?php endforeach;?>
  </div>
  <div id="DM_input">
   <form action="" method="post" enctype="multipart/form-data">
    <input name="DM" class="DM" type="text" maxlength="255">
    <input type="hidden" id="scroll_top" name="scroll_top" value="123" class="st">
    <input type="submit" >
   </form>
   <script>
    $('form').submit(function(){
     var scroll_top = $('#DM_window').scrollTop();
     $('#scroll_top').val(scroll_top);
    });
    window.onload = function(){
     $('#DM_window').scrollTop(<?php echo $_SESSION['scroll'];?>*2);
    }
   </script>
  </div>


 <?php endif;?>

</body>
</html>
