<?php
  require('dbconnect.php');/*DBに接続*/
  session_start();

  if(!empty($_POST)) {/*各項目が入力されているか*/
    if($_POST['name']==''){
      $error['name']='blank';
    }
    if($_POST['email']=='') {
      $error['email']='blank';
    }
    if(strlen($_POST['password'])<7) {
      $error['password']='length';
    }
    if($_POST['password']=='') {
      $error['password']='blank';
    }
  $fileName = $_FILES['image']['name'];/*画像ファイルを入力*/
  if(!empty($fileName)) {
    $ext = substr($fileName, -4);/*入力されたファイルが画像であるかを判定*/
    if ($ext != 'jpeg' && $ext != '.png' && $ext != '.gif') {
      $error['image'] = 'type';
    }
  }

  if(empty($error)) {/*登録済みのメールアドレスでないかを判定*/
    $member = $db -> prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');/*DBに入力されたメールアドレスが何個あるかを所得*/
    $member -> execute(array($_POST['email']));
    $record = $member ->fetch();
    if($record['cnt']!=0){
      $error['email']='duplicate';
    }
  }
  if(empty($error)) {/*プロフィール画像をアップロード*/
    $image = date('YmdHis') . $_FILES['image']['name'];/*ファイル名は登録した時間＋元のファイル名*/
    move_uploaded_file($_FILES['image']['tmp_name'], './member_picuture/'.$image);

    $_SESSION['join'] = $_POST;/*入力した登録情報をセッション保存*/
    $_SESSION['join']['image'] = $image;
    header('Location: check.php');
    exit();
  }
}

if($_REQUEST['action'] == 'rewrite') {/*登録情報の修正に来たときに前に登録した情報を復元*/
 $_POST = $_SESSION['join'];
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
  color:black;
}

tytle{
  color:#00BFFF;
  font-size:40px;
  font-family:'メイリオ';
}

.error_messege{color:#990000;}

label.image{
  background-image:url("../post/dezain_picture/image_input.png");
  background-size:contain;
  vertical-align: middle;
  margin:0 10px 0 0;
  padding:13px 20px;
  display: inline-block;
}
label.submit{
  position:absolute;
  top:550px;
  left:20px;
  background-image:url("../post/dezain_picture/kakuninn.png");
  background-size:contain;
  padding:25px 70px;
  display: inline-block;
}
label input{
 display: none;
}
#file-preview{
  margin-top:10px;
  width:170px;
  text-align: center;
}
body {
  background-image: url("../post/dezain_picture/back.png");
  background-size:cover;
}
</style>
</head>
<tytle>&nbsp;新規登録</tytle>
<body>


<p style='color:white;'>次のフォームに必要事項をご記入ください</p>
<form action="" method="post" enctype="multipart/form-data">

  <span class="koumoku">ニックネーム<span class="required">必須</span></span><br>
  <span><input type="text" name="name" size="35" maxlength="255"
  value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
  </span><br>
  <?php if($error['name'] == 'blank'): ?>
  <span class='error_messege'>*ニックネームを入力してください</span><br>
  <?php endif; ?>
  &nbsp;<br>

  <span class="koumoku">メールアドレス<span class="required">必須</span></span><br>
  <span><input type="text" name="email" size="35" maxlenth="255"
  value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
  </span><br>
  <?php if($error['email'] == 'blank'): ?>
  <span class='error_messege'>*メールアドレスを入力してください</span><br>
  <?php endif; ?>
  <?php if($error['email'] == 'duplicate'): ?>
  <span class='error_messege'>*このメールアドレスは既に登録されています</span><br>
  <?php endif; ?>
  &nbsp;<br>

  <span class="koumoku">パスワード<span class="required">必須</span></span><br>
  <span><input type="password" name="password" size="10" maxlenth="255"
  value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
</span><br>
  <?php if($error['password'] == 'blank' ): ?>
  <span class='error_messege'>*パスワードを入力してください</span><br>
  <?php endif; ?>
  <?php if($error['password'] == 'length' ): ?>
  <span class='error_messege'>*パスワードは７文字以上で入力してください</span><br>
  <?php endif; ?>
  &nbsp;<br>

  <span class="koumoku">プロフィール画像</span>
  <label class="image" for="image">
   <input type="file" name="image" id="image"/>
  </label>
  <script>
   /*プレビュー画像を表示*/
   document.getElementById('image').addEventListener('change', function (e) {
    var file = e.target.files[0];
    var blobUrl = window.URL.createObjectURL(file);
    var img = document.createElement('img');
    img.src = blobUrl;
    img.height = 100;
    document.getElementById('file-preview').appendChild(img);
   });
  </script>
  <div id="file-preview"></div>
  <?php if($error['image'] == 'type' ): ?>
  <span class='error_messege'>
    *ファイル形式が有効ではありません。有効な形式は'png','jepg','gif'です
  </span><br>
  <?php endif; ?>
  <label class="submit" for="submit">
   <input type="submit" id="submit"/>
  </label><br>
</form>
</body>
</html>
