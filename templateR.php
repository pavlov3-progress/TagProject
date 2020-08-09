<!DOCTYPE html>
<html>
  <head>
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="sample.js"></script>
    
    <title>おかえりなさい！</title>
  </head>
  <body>
   <!--本番環境、つまりGitHubへpushする時にはDBのユーザやパスワードはここに書かないようにする-->
     
  <?php
    if (!empty($_POST{"pagetitle"})) {       // ※1 POSTデータを全て受け取りエスケープして変数に入れる 
      foreach($_POST as $k => $v) { 
        if(get_magic_quotes_gpc()) { $v=stripslashes($v); }
          // $v=htmlspecialchars($v);
          $array[$k]=$v; 
      } 
      extract($array);
          
      // 文字コードをUTF-8に変換
      $pagetitle = mb_convert_encoding( htmlspecialchars($pagetitle), "UTF-8","AUTO");
          
      // 置換対象となる独自タグを設定
      $originaltag{"PAGETITLE"} = $pagetitle;
      print_r("ボードID:". $pagetitle);
    }
  ?>

  <p>次回もこのボードに来たい時は、このIDが必要になるから控えておいてくださいね</p>
  <?php
    $pdo = new PDO ("mysql:host=127.0.0.1;dbname=sample_bbs;charset=utf8","root","");

    //DB(board_data)からデータを取得する
    //親ページタイトルと説明を読み出す
    $sql = "SELECT board_name,board_content FROM board_data 
    WHERE id_board = $pagetitle;";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute();
    //
    $title_contents = $stmt -> fetch(PDO::FETCH_ASSOC);
  ?>
  <h1>ボード名 : <?php echo $title_contents["board_name"] ?></h1>
  <h2>説明 : <?php echo $title_contents["board_content"] ?></h2>

  <form>
    <input type="button" onClick="location.href='<?php echo "./dir" ."$pagetitle" ."/templateC.php" ?>'"value="付箋を貼り付けたい">
  </form>

    <?php

      //まずデータベースへ接続する
 //     $pdo = new PDO ("mysql:host=127.0.0.1;dbname=sample_bbs;charset=utf8","root","");

      //DBからデータを取得する
      //board_dataとtag_dataテーブルを結合して、同じ固有のID同士で絞り込んで表示できるようにする
      //つまり作成した親ページと同じ固有idを持った子ページ投稿メッセージだけが、親ページに表示される
      $sql = "SELECT tag_comment,post_name FROM 
      tag_data LEFT JOIN board_data ON board_data.id_board = tag_data.id_tag
      WHERE $pagetitle=tag_data.id_tag;";

      $stmt = $pdo->prepare($sql);
      $stmt -> execute();
      $count = 0;
  
      //取得したデータをすべて$rowの配列に代入
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
      //print_r($row);
    
    ?>
    
    <div id="draggable<?php echo $count ?>" class="ui-widget-content">
      <div class="husen">
        <?php print_r($row); ?>

      </div>
    </div>

    <?php
      $count = $count +1;
      }
    ?>

    <script>
      setTimeout("location.reload()",10000);
    </script>
  </body>
</html>