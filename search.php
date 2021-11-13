<?php
require('dbconnect.php');
session_start();
require('functions.php');
?>

<!DOCTYPE html>
<html>
<head>
   <style>
     .search{font-size:30px;text-align:center;margin-top:80px;}
     input{
       width:80%; padding: 5px 8px;
       border-radius: 6px;
       border-top: 1px solid #aaa;
       border-left: 1px solid #aaa;
       border-right: 2px solid #aaa;
       border-bottom: 2px solid #aaa;
       background-image: none;
       background-color:white;
       font-size: 16px;
       }
     .message{width:100%}
     input[type="radio"]{display:none;}
     .user_block{display:none;}
     .message_block{display:none;}
     #tab_btn1:checked ~.tab .tab1_label{color:#00BFFF;text-decoration:underline;}
     #tab_btn1:checked ~.message_block{display:block;}
     #tab_btn2:checked ~.tab .tab2_label{color:#00BFFF;text-decoration:underline;}
     #tab_btn2:checked ~.user_block{display:block;}
     .tab{background-color:white;width:30%;
       margin-left:auto;margin-right:auto;}
     .tab label{
       width:35%; margin: 5% 7%;
       display:inline-block;
       text-align: center;
       font-size:20px;font-family:"メイリオ";
       cursor:pointer; transition:ease 0.1s;
     }
     .search_none{width:60%;margin-left: auto;margin-right: auto;
       text-align: center;font-size:20pt;}
   </style>
</head>

<body>
  <?php require('menu.php');?>
   <div class="search">
    <form action="" method="post">
     <input name="keyword" type="text" placeholder="キーワード検索"  maxlength="255">
    </form>
   </div>
 <?php if($_POST['keyword']!=''):?>
  <div>
     <input id="tab_btn1" name="tab_btn"type="radio" checked>
     <input id="tab_btn2" name="tab_btn"type="radio">
    <div class="tab">
     <label class="tab1_label" for="tab_btn1">投稿</label>
     <label class="tab2_label" for="tab_btn2">ユーザー</label>
    </div>
     <hr>


   <div class="message_block">
     <?php
       $counts = $db-> prepare('SELECT count(*) AS count FROM posts WHERE message LIKE ?');
       $counts -> execute(array('%'.$_POST['keyword'].'%'));
       $count = $counts -> fetch();
       $searches = $db -> prepare('SELECT p.*, m.name, m.picture
         FROM posts p, members m
         WHERE m.id=p.member_id AND message LIKE ?');
       $searches -> execute(array('%'.$_POST['keyword'].'%'));
     ?>
     <?php if($count['count']==0):?>
      <div class="search_none">
        <p>キーワードに当てはまるコメントがいませんでした</p>
      </div>
     <?php endif;?>
     <?php foreach($searches as $post):?>
       <div class='message'>
         <?php require('div_message.php');?>
       </div>
     <?php endforeach;?>
   </div>
   <div class="user_block">
     <?php
       $counts = $db-> prepare('SELECT count(*) AS count FROM members
         WHERE (profile_messsage LIKE ?) || (name LIKE ?)');
       $counts -> execute(array('%'.$_POST['keyword'].'%','%'.$_POST['keyword'].'%'));
       $count = $counts -> fetch();
       $searches = $db -> prepare('SELECT * FROM members
         WHERE (profile_messsage LIKE ?) || (name LIKE ?)');
       $searches -> execute(array('%'.$_POST['keyword'].'%','%'.$_POST['keyword'].'%'));
    ?>
     <?php if($count['count']==0):?>
      <div class="search_none">
       <p>キーワードに当てはまる会員がいませんでした</p>
      </div>
     <?php endif;?>
     <?php foreach ($searches as $post):?>
       <div class='message'>
         <a style="text-decoration:none;"
           href="profile.php?id=<?php echo h($post['id']);?>">
         <p><img style="vertical-align:middle; border-radius:20px;"
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
  <?php endif; ?>
 </body>
 </html>
