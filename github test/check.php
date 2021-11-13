<?php
session_start();
require('dbconnect.php');

if(!isset($_SESSION['join'])) {
  header('Location:index.php');
  exit();
}
if(!empty($_POST)) {
  $statement = $db -> prepare('INSERT INTO members set name=?, email=?,
    password=?, picture=?, created=NOW()');
  $statement -> execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    sha1($_SESSION['join']['password']),
    $_SESSION['join']['image']
  ));
  echo $_POST['action'];
unset($_SESSION['join']);
header('Location: thank.php');
exit();
}
?>

<!doctype html>
<html>
<head>
<style>
.required {
  color:white;
  background-color: #990000;
}
.koumoku{
color:#00BFFF;
}
tytle{
  background:white;
  color:#00BFFF;
  font-size:40px;
  font-family:'メイリオ';
}
label.submit{
  position:absolute;
  top:550px;
  left:200px;
  background-image:url("../post/dezain_picture/touroku.png");
  background-size:contain;
  padding:25px 70px;
  display: inline-block;
}
label input{
 display: none;
}

.button a{
  position:absolute;
  top:550px;
  left:20px;
}
body {
  background-image: url("../post/dezain_picture/back.png");
  background-size:cover;
}
</style>
</head>
<tytle>新規登録</tytle>
<body>


<p>入力内容をご確認ください</p>
<form action="" method="post">
  <input type="hidden" name="action" value="submit" />
  <div class="koumoku_block">
   <span class="koumoku">ニックネーム</span><br>
   <?php  echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?><br>
   &nbsp;<br>
   <span class="koumoku">メールアドレス</span><br>
   <?php  echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?><br>
   &nbsp;<br>
   <span class="koumoku">パスワード</span><br>
   <span>【表示されません】</span><br>
   &nbsp;<br>
   <span class="koumoku">写真など</span><br>
   <p><img src="member_picuture/<?php
   echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>"
   width="100" height="100" alt="トプ画" /></p>
   &nbsp;<br>
  </div>
  <div class="button">
    <label class="submit" for="submit">
     <input type="submit" id="submit"/>
    </label>
    <a href="index.php?action=rewrite">
     <img src="../post/dezain_picture/modoru.png" height="50"/>
    </a>
  </div>
</form>

</body>
</html>
