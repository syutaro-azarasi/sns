<?php
require('dbconnect.php');
session_start();
/*
if($_COOKIE['email'] != ''){
  $_POST['email'] = $_COOKIE['email'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] = 'on';
}
*/
if(!empty($_POST)) {

 if($_POST['email'] != '' && $_POST['password'] != '') {
   $login = $db ->prepare('SELECT * FROM members WHERE email=?
   AND password=?');
   $login -> execute(array(
     $_POST['email'],
     sha1($_POST['password'])
   ));

   $member = $login->fetch();

  if($member) {
     $_SESSION['id'] = $member['id'];
     $_SESSION['time'] = time();
/*
if($_POST['save']=='on') {
  setcookie('email', $_POST['email'], time()+60*60*24*14);
  setcookie('password', $_POST['password'], time()+60*60*24*14);
}*/
     header('Location: ../post/index.php');
     exit();
  }

  else {
     $error['login'] ='failed';
  }

 }
 else{
  $error['login']='blank';
 }

}
?>

<!doctype html>
<html>
<head>
<style>

body {
  background:white;
}

.tytle{
  position:absolute;
  top:5%;
  width:100%;
  color:#00BFFF;
  font-size:20pt;
  font-family:'arial';
  text-align:center;
}
.sentence{
  color:black;
  font-family:'メイリオ';
}

.login
{
  position:absolute;
  top:0;
  right:0;
  bottom:0;
  left:0;
  margin:auto;
  width:40%;
  height:60%;
  box-shadow:0px 0px 1px;
}

.login_form_block
{

 position:absolute;
 top:25%;
 width:100%;
 height:40%;
}
.login_form
{
 right:0;
 left:0;
 margin:auto;
 background:;
 width:80%;
}

.login_form
input{
  width:100%;
  height:30px;
}
.check{
  font-size:10pt;
  position:absolute;
  top:90%;
  right:0;
  left:0;
  width:80%;
  margin:auto;
}
.login_botton
{
  background:#00BFFF;
  color:white;

  position:absolute;
  top:75%;
  right:0;
  left:0;
  margin:auto;

  border-radius:15px;
  border: 1px solid #ccc;
  width:80%;
  height:30px;

  text-align:center;
  font-size:12pt;
}
.lead{
  font-size:10pt;
  position:absolute;
  top:82%;
  left:0;
  right:0;
  margin:auto;
  width:80%;
}
a{
  text-decoration:none;
  color:#00BFFF;
}

.error_messege{color:#990000;}
</style>
</head>
<body>


<div class="login">
 <div class="tytle"><b>ログイン</b></div>
 <div class="login_form_block">
  <form action="" method="post">
  <div class="login_form">
   <label for="email">メールアドレス</label><br>
   <input type="text" name="email"  maxlength="255"
    value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"/>
  </div>
  &nbsp;<br>
  &nbsp;<br>
  <div class="login_form">
   <label for="password">パスワード</label></br>
   <input type="password" name="password" maxlength="255"/><br>
  </div>
  <div class="check">
  <input type="checkbox" name="save" value="on">次回から自動的にログインする
  </div>
  <?php if($error['login']=='failed'):?>
   <span class="error_messege">メールアドレスまたはパスワードが間違っています</span>
  <?php endif;?>
 </div>
  <input class="login_botton" type="submit" value="GO"/>
 <div class="lead">
  <p class="sentence">登録がまだの方はこちらから
  <a href="index.php"><b>新規登録</b></a></p>
 </div>
</div>



</form>
</body>
</html>
<//body>
