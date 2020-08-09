<html>
  <head>
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="sample.js"></script>

    <title>付箋</title>
  </head>
  <body>

    <form method="POST" action="comments.php" name="comment_form">
      <input type="hidden" name="pid" value="<%PAGEID>">
      名前：<input type="text" name="cname" size="20" required><br>
      メッセージ：<br>
      <textarea name="ctext" rows="5" cols="30" required></textarea><br>
      送信先ボードのID：<input type="text" name="id_tag" size="5" required><br>
      <input type="submit" value="付箋を貼りつける" >
    </form>
  
  </body>
</html>