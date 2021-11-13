<?php
require('dbconnect.php');
session_start();
require('index_php.php');
require('functions.php');
?>
<!DOCTYPE html>
<html>
<head>
<style>
.sentence {
  color:white;
}

.tytle{
  background: white;
  color:#00BFFF;
  font-family:'メイリオ';
}
label.image{
  background-image:url("dezain_picture/image_input.png");
  background-size:contain;
  padding:13px 20px;
  display: inline-block;
}
label.submit{
  background-image:url("dezain_picture/toukou.png");
  background-size:contain;
  padding:14px 39px;
  display: inline-block;
}
label input{
 display: none;
}

</style>
</head>

<div class="tytle"><tytle>&nbsp;フォロワーに向けてメッセージを投稿しよう</tytle></div>
<body>

  <?php if($end=='T'):?>
   <script type="text/javascript">
    window.opener.doReload();
    window.close();
   </script>
  <?php endif;?>

  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="switch" value="T" />
    <textarea style="border:none;" name="message" cols="80" rows="10" >
    </textarea><br>
    <label class="image" for="image">
     <input type="file" name="filename" id="image"/>
    </label>
    <script>
     document.getElementById('image').addEventListener('change', function (e) {
      var file = e.target.files[0];
      var blobUrl = window.URL.createObjectURL(file);
      var img = document.createElement('img');
      img.src = blobUrl;
      img.height = 200;
      document.getElementById('file-preview').appendChild(img);
     });
    </script>
    <label class="submit" for="submit">
     <input type="submit" id="submit" value="投稿する" />
    </label><br>
    <div id="file-preview"></div>
  </form>


</body>
</html>
