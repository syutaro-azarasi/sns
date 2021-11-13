<?php
function h($value) {
   return htmlspecialchars($value, ENT_QUOTES);
}
function makeLink($value) {
  return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>' , $value);
}
?>
<script>
function win_open(value) {
   var subw = 800;   // サブウインドウの横幅
   var subh = 500;   // サブウインドウの高さ
   var subx = ( screen.availWidth  - subw ) / 2;   // X座標
   var suby = ( screen.availHeight - subh ) / 2;   // Y座標
   // サブウインドウのオプション文字列を作る
   var SubWinOpt = "width=" + subw + ",height=" + subh + ",top=" + suby + ",left=" + subx;
　 window.open(value,'投稿',SubWinOpt);
　}

function doReload() {
  location.reload();
  }
function loca(value){
  window.location.href = value;
  }
function ch_css(value1,value2){
  document.getElementById(value1).classList.add(value2);
  }
function loca_conf(value1,value2){
  var conf =
   window.confirm(value1);
   if(conf){
　　 location.href=value2;
　 }
  }
function scroll(){
window.scrollTo(0,1000);
 }
</script>
