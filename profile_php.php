<?php

$posts = $db -> prepare('SELECT p.*, m.* FROM posts p, members m
WHERE m.id=? AND p.member_id=m.id  ORDER BY p.created DESC');
$posts -> execute(array($_REQUEST['id']));
$post = $posts->fetch();

if($_REQUEST['flag']==T){
  $follow = $db -> prepare('INSERT INTO follow
    SET follower=?,
        follow=?,
        created=NOW()');
  $follow -> execute(array($_SESSION['id'],$_REQUEST['id']));
  $set_follower= $db -> prepare('UPDATE members
    SET follower_count=follower_count+1
    WHERE id=?');
  $set_follower -> execute(array($_REQUEST['id']));
  $set_follow= $db -> prepare('UPDATE members
    SET follow_count=follow_count+1
    WHERE id=?');
  $set_follow -> execute(array($_SESSION['id']));
  $url = "profile.php?id=" . $_REQUEST['id'];
  header("Location:" . $url );
  exti();
}

if($_REQUEST['DM_req']==T){
  $Dm_req = $db -> prepare('INSERT INTO DM_request
    SET request_member=?,
        requested_member=?,
        created=NOW()');
  $Dm_req -> execute(array($_SESSION['id'],$_REQUEST['id']));
  header("Location:profile.php?id=".$_REQUEST['id']);
  exti();
}

if($_REQUEST['flag']==F){
  $follow = $db -> prepare('DELETE FROM follow WHERE follower=? AND follow=?');
  $follow -> execute(array($_SESSION['id'],$_REQUEST['id']));
  $delete_follower= $db -> prepare('UPDATE members
    SET follower_count=follower_count-1
    WHERE id=?');
  $delete_follower -> execute(array($_REQUEST['id']));
  $delete_follow= $db -> prepare('UPDATE members
    SET follow_count=follow_count-1
    WHERE id=?');
  $delete_follow -> execute(array($_SESSION['id']));
  $url = "profile.php?id=" . $_REQUEST['id'];
  header("Location:" . $url);
  exti();
}
?>
