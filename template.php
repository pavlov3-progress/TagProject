<!DOCTYPE html>
<html>
<head>
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="sample.js"></script>
<title><%PAGETITLE></title>
</head>
<body>
<h1>ボード名 : <%PAGETITLE></h1>

<h2>説明 : <%PAGECONTENTS></h2>

<form>
<input type="button" onClick="location.href='templateC.php'" value="付箋を貼り付けたい">
</form>

<?php

$result = glob('*.dat');    //フォルダのdatファイルを変数の配列に格納
//var_dump($result);
$count = 0;
foreach($result as $value){

    $fp2 = fopen($value,"r");   //ファイルを開く
    $txt = fgets($fp2);         //中のテキストを変数に入れる
?>
  <div id="draggable<?php echo $count ?>" class="ui-widget-content">
    <div class="husen">
      <?php echo $txt ?>

    </div>
  </div>

<?php
fclose($fp2);
$count = $count +1;
}
?>

<script>
setTimeout("location.reload()",10000);
</script>
</body>
</html>