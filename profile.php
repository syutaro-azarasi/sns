<?php
require('dbconnect.php');
session_start();
require('profile_php.php');
require('functions.php');
?>

<!DOCTYPE html>
<html>
<head>
<style>

.edit{width:30px;height:30px;vertical-align: middle;}
.none{display:none;}
.bottun_tytle{
  text-decoration:none;
  color:#00BFFF;
  font-size:20px;
  font-family:'メイリオ';
}
.button{
  background-color: white;
  text-align:center;
  border-radius:10px;
}

.sentence {color:white;}
.profile{background-color: white;}
.user_name{
  font-size:30pt;
}
.profile_name{
  text-align: center;
}
.profile_picture{
  margin-top:150px;
  width:300px;
  height: 300px;
  margin-left: auto;
  margin-right: auto;
  }
.profile_picture img{
  width:300px;
  height: 300px;
	object-fit:cover;
  border:0.5px solid black;
  border-radius:50%;
}

input[type="radio"]{display:none;}
.tab_area{ margin:0 10px; text-align:center;}
.tab_area label{
  display:inline-block;
  width:150px; margin:10px;
  padding:10px 10px; color:black;
  font-size:20px;
  font-family:"メイリオ";
  cursor:pointer; transition:ease 0.1s;
  }

.panel_area{background:#fff;}
.tab_panel{width:100%;display:none;}

#tab1:checked ~ .tab_area .tab1_label{color:#00BFFF;text-decoration: underline;}
#tab1:checked ~ .panel_area #panel1{display:block;}
#tab2:checked ~ .tab_area .tab2_label{color:#00BFFF;text-decoration: underline;}
#tab2:checked ~ .panel_area #panel2{display:block;}
#tab3:checked ~ .tab_area .tab3_label{color:#00BFFF;text-decoration: underline;}
#tab3:checked ~ .panel_area #panel3{display:block;}
#tab4:checked ~ .tab_area .tab4_label{color:#00BFFF;text-decoration: underline;}
#tab4:checked ~ .panel_area #panel4{display:block;}
#tab5:checked ~ .tab_area .tab5_label{color:#00BFFF;text-decoration: underline;}
#tab5:checked ~ .panel_area #panel5{display:block;}
</style>

</head>

<body>
 <?php require('menu.php');?>
 <div class="profile_picture">
    <img src="../join/member_picuture/<?php echo h($post['picture']);?>"/>
 </div>

 <div class="profile_name">
  <span class="user_name">
    <?php echo h($post['name']);?>
  </span><br>
  <span>フォロー：<?php echo h($post['follow_count']);?></span>
  &nbsp;&nbsp;
  <span>フォロワー：<?php echo h($post['follower_count']);?></span><br>
  &nbsp;<br>
  <?php if($_SESSION['id']!=$_REQUEST['id']):?>
   <?php
    $followers= $db -> prepare('SELECT count(*) AS count FROM follow WHERE follower=? AND follow=?');
    $followers -> execute(array($_SESSION['id'],$_REQUEST['id']));
    $follower = $followers -> fetch();
    $DM_request= $db -> prepare('SELECT count(*) AS count FROM DM_request
      WHERE request_member=? AND requested_member=?');
    $DM_request -> execute(array($_SESSION['id'],$_REQUEST['id']));
    $DM_request = $DM_request -> fetch();?>
    <?php if($DM_request['count']==0):?>
     <img id="request" style="vertical-align:middle;" src="dezain_picture/DM_request.png"
        width="40" height="40"
        onclick="loca_conf('マッチングのリクエストを送ります',
         'profile.php?id=<?php echo $_REQUEST['id'];?>&DM_req=T')">
    <?php endif;?>
   <?php if($follower['count']==0):?>
    <button class="button">
     <a class="bottun_tytle" href="profile.php?flag=T&id=<?php echo h($_REQUEST['id']);?>">
      フォローする</a>
    </button>
    <?php else:?>
    <button class="button">
     <a class="bottun_tytle" href="profile.php?flag=F&id=<?php echo h($_REQUEST['id']);?>">
      フォロー中</a><br>
    </button>
    <?php endif;?>
  <?php else:?>
    <img class="edit" src="dezain_picture/haguruma.png"
      onclick="loca('profile_edit.php?id=<?php echo h($post['id']);?>')">
    <button class="button">
      <a class="bottun_tytle" href="../join/login.php">
      ログアウト
      </a>
    </button>
　 <?php endif;?>
   <hr>
   <p><?php echo makeLink(h($post['profile_messsage']));?></p>
   <hr>
 </div>

 <div class="tab_wrap">
	<input  id="tab1" type="radio" name="tab_btn" checked>
	<input  id="tab2" type="radio" name="tab_btn">
	<input  id="tab3" type="radio" name="tab_btn">
  <input  id="tab4" type="radio" name="tab_btn">
  <input  id="tab5" type="radio" name="tab_btn">

	<div class="tab_area">
		<label class="tab1_label" for="tab1">人気投稿</label>
		<label class="tab2_label" for="tab2">投稿</label>
    <label class="tab3_label" for="tab3">メディア投稿</label>
		<label class="tab4_label" for="tab4">フォロー</label>
    <label class="tab5_label" for="tab5">フォロワー</label>
	</div>
  <hr>

	<div class="panel_area">
		<div id="panel1" class="tab_panel">
     <?php
     $post1s = $db -> prepare('SELECT p.*, m.name, m.picture
     FROM posts p, members m
     WHERE p.member_id=m.id AND p.member_id=?
     ORDER BY p.like_count DESC LIMIT 5');
     $post1s -> execute(array($_REQUEST['id']));
     foreach($post1s as $post):?>
      <?php require('div_message.php');?>
     <?php endforeach;?>
		</div>

    <div id="panel2" class="tab_panel">
     <?php
     $post2s = $db -> prepare('SELECT p.*, m.name, m.picture FROM posts p, members m
      WHERE m.id=? AND p.member_id=m.id  ORDER BY p.created DESC');
     $post2s -> execute(array($_REQUEST['id']));
      foreach($post2s as $post):?>
      <?php require('div_message.php');?>
     <?php endforeach;?>
		</div>

		<div id="panel3" class="tab_panel">
      <?php
      $post3s = $db -> prepare('SELECT p.*, m.name, m.picture FROM posts p, members m
        WHERE p.member_id=m.id
        AND p.member_id=?
        AND p.filename IS NOT NULL
        ORDER BY p.created DESC');
      $post3s -> execute(array($_REQUEST['id']));
      foreach($post3s as $post):?>
       <?php require('div_message.php');?>
      <?php endforeach;?>
		</div>

    <div id="panel4" class="tab_panel">
      <?php
      $post4s = $db -> prepare('SELECT m.id, m.name, m.picture, m.profile_messsage, f.follower, f.created
        FROM members m, follow f
        WHERE m.id=f.follow AND f.follower=?
        ORDER BY f.created DESC');
      $post4s -> execute(array($_REQUEST['id']));
       foreach($post4s as $post):?>
      <div class="comment_member">
       <a style="text-decoration:none;"
         href="profile.php?id=<?php echo h($post['id']);?>">
       <p><img style="vertical-align: middle; border-radius:20px;"
        src="../join/member_picuture/<?php echo h($post['picture']);?>"
        width="40" height="40"/>
       </a>
        <?php echo h($post['name']);?>
       </p><br>
       <span><?php echo h($post['profile_messsage']);?></span><br>
       <hr>
     </div>
     <?php endforeach;?>
		</div>

    <div id="panel5" class="tab_panel">
      <?php
      $post5s = $db -> prepare('SELECT m.id, m.name, m.picture, m.profile_messsage, f.follow, f.created
        FROM members m, follow f
        WHERE m.id=f.follower AND f.follow=?
        ORDER BY f.created DESC');
      $post5s -> execute(array($_REQUEST['id']));
       foreach($post5s as $post):?>
       <div class="comment_member">
       <a style="text-decoration:none;"
         href="profile.php?id=<?php echo h($post['id']);?>">
       <p><img style="vertical-align: middle; border-radius:20px;"
        src="../join/member_picuture/<?php echo h($post['picture']);?>"
        width="40" height="40"/>
       </a>
        <?php echo h($post['name']);?>
       </p><br>
       <span><?php echo h($post['profile_messsage']);?></span><br>
       <hr>
      </div>
      <?php endforeach;?>
		</div>

	</div>
</div>

</body>
</html>
