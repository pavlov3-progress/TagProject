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
        <!--本番環境、つまりGitHubへpushする時にはDBのユーザやパスワードはここに書かないようにする-->
     
      <?php
        //まずデータベースへ接続する
        $pdo = new PDO ("mysql:host=env('DB_HOST');dbname=env('DB_DATABASE');charset=utf8","env('DB_USERNAME')","env('DB_PASSWORD')");
        //DBからデータを取得する（最後の行から1行だけ）
        $sql = "SELECT id_board FROM board_data ORDER BY id DESC LIMIT 1;";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute();
        
        //取得したデータを表示してみる
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        $pagetitle = $row["id_board"];
        echo("<br/>");
      ?>

    <p>この親ページに入る時、そしてメッセージの送信時に、このIDが必要になるので覚えておいてください</p><br/>
    <p>ボードID : <?php echo $row["id_board"] ?></p>
      

    

    <form>
      <input type="button" onClick="location.href='templateC.php'" value="付箋を貼り付けたい">
    </form>

    <?php

      //まずデータベースへ接続する
      $pdo = new PDO ("mysql:host=env('DB_HOST');dbname=env('DB_DATABASE');charset=utf8","env('DB_USERNAME')","env('DB_PASSWORD')");

      //DBからデータを取得する
      //board_dataとtag_dataテーブルを結合して、同じ固有のID同士で絞り込んで表示できるようにする
      //つまり作成した親ページと同じ固有idを持った子ページ投稿メッセージだけが、親ページに表示される
      $sql = "SELECT tag_comment,post_name FROM 
      tag_data LEFT JOIN board_data ON board_data.id_board = tag_data.id_tag
      WHERE $row[id_board]=tag_data.id_tag;";

      $stmt = $pdo->prepare($sql);
      $stmt -> execute();
      $count = 0;
  
      //取得したデータをすべて表示してみる
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
    <form action="../templateR.php" method="POST">
      <input type="hidden" name="pagetitle">
      <input type="submit" value="更新" />
    </form>
  </body>
</html>