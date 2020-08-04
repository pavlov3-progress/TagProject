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
    <h1>ボードのID : <%PAGETITLE></h1><!--update: DBからid_boardを読みだしてここに表示-->
        <!--本番環境、つまりGitHubへpushする時にはDBのユーザやパスワードはここに書かないようにする-->

        
        <?php
        //まずデータベースへ接続する
        $pdo = new PDO ("mysql:host=127.0.0.1;dbname=sample_bbs;charset=utf8","root","");

        //DBからデータを取得する（最後の行から1行だけ）
        $sql = "SELECT id_board FROM board_data ORDER BY id DESC LIMIT 1;";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute();
        
/*      取得したデータを試験的にすべて表示してみる
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        print_r($row);
        }
*/
        //取得したデータを表示してみる
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        print_r($row);
        echo("<br/>");
        ?>

        <p>次回もこのボードに来たい時は、このIDが必要になるから控えておいてくださいね</p>

    <h2>説明 : <%PAGECONTENTS></h2>

    <form>
      <input type="button" onClick="location.href='templateC.php'" value="付箋を貼り付けたい">
    </form>
<!--以下は作成されたdatファイルを開いて中のテキストを表示している-->
    <?php

    //まずデータベースへ接続する
    $pdo = new PDO ("mysql:host=127.0.0.1;dbname=sample_bbs;charset=utf8","root","");

    //DBからデータを取得する
    $sql = "SELECT tag_comment,post_name FROM tag_data ORDER BY id_tag ;";
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

    <script>
      setTimeout("location.reload()",10000);
    </script>
  </body>
</html>