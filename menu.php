<?php
$members = $db -> prepare('SELECT id, picture FROM members WHERE id=?');
$members -> execute(array($_SESSION['id']));
$member = $members -> fetch();
?>
<style>
.tytle{
  background-color:white;
  position:fixed;
  top:0px;
  width:100%;
  padding-top:20px;
  padding-bottom: 20px;
  display: flex;
  justify-content: space-evenly;
  z-index: 10;
  box-shadow:1px 1px 2px;
}


.member_picuture{
  width:40px;
  height:40px;
  border-radius:50%;
  vertical-align: middle;
}

</style>
<div class="tytle">
   <img class="member_picuture" src="../join/member_picuture/<?php echo h($member['picture']);?>"
    onclick="loca('profile.php?id=<?php echo h($member['id']);?>')">
   <img style="width:40px;height:40px;" src="dezain_picture/home_icon.png" onclick="loca('index.php')">
   <img style="width:40px;height:40px;" src="dezain_picture/search.png" onclick="loca('search.php')">
   <img style="width:50px;height:40px;" src="dezain_picture/DM.png" onclick="loca('DM.php')">
 </div>
