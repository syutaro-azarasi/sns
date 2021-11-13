<?php
require('dbconnect.php');
session_start();
require('functions.php');
$posts = $db -> prepare('SELECT m.* FROM  members m WHERE m.id=?');
$posts -> execute(array($_REQUEST['id']));
$post = $posts ->fetch();

  if(!empty($_POST)) {
    if($_POST['name']==''){
      $error['name']='blank';
    }
    $fileName = $_FILES['image']['name'];
    if(!empty($fileName)) {
     $ext = substr($fileName, -4);
     if ($ext != 'jpeg' && $ext != '.png' && $ext != '.gif') {
       $error['image'] = 'type';
     }
    }

  if(empty($error)) {
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../join/member_picuture/'.$image);
    $_SESSION['edit'] = $_POST;
    $_SESSION['edit']['edit_image'] = $image;
    header('Location:edit_check.php');
    exit();
  }
}
if($_REQUEST['action']=='rewrite'){
  $_POST=$_SESSION['edit'];
  unlink("../join/member_picuture/".$_SESSION['edit']['edit_image']);
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
      <img src="../join/member_picuture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES);?>"/>
  </div>

<div class="input_edit">
<form action="" method="post" enctype="multipart/form-data">
  <span class="koumoku">ニックネーム</span><br>
  <span><input style="font-size:15pt;" type="text" name="name" size="50" maxlength="300"
    value="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>" />
  </span><br>
  <?php if($error['name'] == 'blank'): ?>
   <span class='error_messege'>*ニックネームを入力してください</span><br>
  <?php endif; ?>
  &nbsp;<br>
  <textarea name="profile_comment" cols="100" rows="10">
  <?php echo htmlspecialchars($post['profile_messsage'], ENT_QUOTES); ?>
  </textarea><br>
  <span class="koumoku">写真</span><br>
  <span><input type="file" name="image" size="35" /></span><br>
  <?php if($error['image'] == 'type' ): ?>
   <span class='error_messege'>
    *ファイル形式が有効ではありません。有効な形式は'png','jepg','gif'です
   </span><br>
  <?php endif; ?>
  &nbsp;<br>
  <input type="hidden" nane="submit" value="submit"/>
　<input type="submit" value="入力内容を確認する" />
  <a href="profile.php?id=<?php echo h($_SESSION['id']) ?>">戻る</a>
</form>
</div>

&nbsp;<br>&nbsp;<br>&nbsp;<br>
<div style="background-color:#00BFFF;">
  &nbsp;<br>&nbsp;<br>&nbsp;<br>
</div>
</body>
</html>
