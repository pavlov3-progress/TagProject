<html>
	<head>
		<title>メッセージ送信</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<?php
			if ($_POST{"ctext"}) {
				// POSTデータを全て受け取りエスケープして変数に入れる 
				foreach($_POST as $k => $v) { 
					if(get_magic_quotes_gpc()) { $v=stripslashes($v); }
						$v=htmlspecialchars($v);
						$array[$k]=$v; 
					} 
				extract($array);

				// 文字コードをUTF-8に変換
				$ctext = mb_convert_encoding($ctext, "UTF-8","AUTO");
				$cname = mb_convert_encoding($cname, "UTF-8","AUTO");
				// 改行を<br>タグに
				$ctext = nl2br($ctext);

				// メッセージ内容を編集
				$comment = $ctext . " ";
				$comment.= $cname . " ";
				$pdo = new PDO ("mysql:host=env('DB_HOST');dbname=env('DB_DATABASE');charset=utf8","env('DB_USERNAME')","env('DB_PASSWORD')");
			
				//データベースに書きこむ
				$sql = "INSERT INTO tag_data (tag_comment,post_name,id_tag) VALUES (:ctext,:cname,:id_tag);";
				$stmt = $pdo->prepare($sql);
				$stmt -> bindParam(":ctext", $ctext, PDO::PARAM_STR);
				$stmt -> bindParam(":cname",$cname, PDO::PARAM_STR);
				$stmt -> bindParam(":id_tag", $id_tag, PDO::PARAM_STR);
				$stmt -> execute();
			}
		?>
		<br/>
		<span id="view_time"></span>

		<script type="text/javascript">
			document.getElementById("view_time").innerHTML = getNow();

			function getNow() {
				var now = new Date();
				var year = now.getFullYear();
				var mon = now.getMonth()+1; //１を足すこと
				var day = now.getDate();
				var hour = now.getHours();
				var min = now.getMinutes();
				var sec = now.getSeconds();

				//出力用
				var s = year + "年" + mon + "月" + day + "日" + hour + "時" + min + "分" + sec + "秒"; 
				return s;
			}
		</script>
		<form>
			<input type="button" onClick="location.href='<?php echo  $id_tag . ".php" ?>'" value="親ページに戻ります">
		</form>
	</body>
</html>









