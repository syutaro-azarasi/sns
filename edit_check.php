<?php
require('dbconnect.php');
session_start();
if(!empty($_POST)){
  if(!empty($_SESSION['edit']['name'])){
    $edit = $db -> prepare('UPDATE members SET name=? WHERE id=?');
    $edit -> execute(array($_SESSION['edit']['name'], $_SESSION['id']));
  }
  if(!empty($_SESSION['edit']['profile_comment'])){
    $edit = $db ->prepare('UPDATE members SET profile_messsage=? WHERE id=?');
    $edit -> execute(array($_SESSION['edit']['profile_comment'], $_SESSION['id']));
  }
  if(preg_match('([a-z]+$)', $_SESSION['edit']['edit_image'])){
    $delete = $db ->prepare('SELECT picture FROM members WHERE id=?');
    $delete -> execute(array($_SESSION['id']));
    $delete_picture = $delete -> fetch();
    unlink('../join/member_picuture/'.$delete_picture['picture']);
    $edit = $db ->prepare('UPDATE members SET picture=? WHERE id=?');
    $edit -> execute(array($_SESSION['edit']['edit_image'], $_SESSION['id']));


  }
  header("Location:profile.php?id=".$_SESSION['id']);
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <style>
  .tytle{
    background: white;
    color:#00BFFF;
    font-size:40px;
    font-family:'メイリオ';
  }

  .error_messege{color:#990000;}

  .user_name{
    font-size:30pt;
  }

  .profile_picture{
    margin-top: 150px;
    width:300px;
    height: 300px;
    display: inline-block;
    }

  .input_edit{
    display: inline-block;
    position: relative;
    left: 200px;
    }
  .profile_picture img{
    width:300px;
    height: 300px;
  	object-fit:cover;
    border:0.5px solid black;
    border-radius:50%;
  }
  .profile_name{
    background-color:#00BFFF;
    text-align: center;
  }

  </style>

</head>

<div class="tytle"><tytle>&nbsp;プロフィール編集</tytle></div>
<body>

  <div class="profile_picture">
      <img
       src="../join/member_picuture/<?php echo htmlspecialchars($_SESSION['edit']['edit_image'] , ENT_QUOTES);?>"/>
  </div>

<div class="input_edit">
  <form action="" method="post">
    <input type="hidden" name="action" value="submit" />
    <span class="koumoku">ニックネーム</span><br>
    <span><?php echo htmlspecialchars($_SESSION['edit']['name'], ENT_QUOTES); ?></span>
    &nbsp;<br>
    <span class="profile_comment">自己紹介</span><br>
    <span><?php echo htmlspecialchars($_SESSION['edit']['profile_comment'], ENT_QUOTES); ?></span>
    &nbsp;<br>
    <div>
      <a href="profile_edit.php?id=<?php echo htmlspecialchars($_SESSION['id'], ENT_QUOTES);?>&action=rewrite">書き直す</a>
      |
  　  <input type="submit" value="登録する" />
    </div>
  </form>
</div>

&nbsp;<br>&nbsp;<br>&nbsp;<br>
<div style="background-color:#00BFFF;">
  &nbsp;<br>&nbsp;<br>&nbsp;<br>
</div>
</body>
</html>
