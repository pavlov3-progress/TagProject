<html>
	<head>
		<title>親ページ作成</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
<html>

	<body>

<!--  update:DBにidとid_boardとboard_nameとboard_contentを追加する処理-->
<?php

	$template = "template.php";	// テンプレートファイル名
	$pageid = null;
	if ($_POST{"pagetitle"}) {
		// ※1 POSTデータを全て受け取りエスケープして変数に入れる 
		foreach($_POST as $k => $v) { 
			if(get_magic_quotes_gpc()) { $v=stripslashes($v); }
				// $v=htmlspecialchars($v);
				$array[$k]=$v; 
			} 
		extract($array);

		// 文字コードをUTF-8に変換
		$honbun = mb_convert_encoding($honbun, "UTF-8","AUTO");
		$pagetitle = mb_convert_encoding( htmlspecialchars($pagetitle), "UTF-8","AUTO");

		// 改行を<br>タグに変換
		$honbun = nl2br($honbun);

		// 置換対象となる独自タグを設定
		$originaltag{"PAGETITLE"} = $pagetitle;
		$originaltag{"PAGECONTENTS"} = $honbun;
		$originaltag{"PAGEID"} = $pageid;

		// 乱数を生成してファイル名に
		$pageid = rand( 10000, 99999);
		$filename = $pageid . ".php";

		//データベースに接続する
		$pdo = new PDO("mysql:host=127.0.0.1;dbname=sample_bbs;charset=utf8","root","");
			
		//データベースに書きこむ
		$sql = "INSERT INTO board_data (id_board,board_name,board_content) VALUES (:pageid,:pagetitle,:honbun);";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindValue(":pageid",$pageid, PDO::PARAM_INT);
		$stmt -> bindParam(":pagetitle", $pagetitle, PDO::PARAM_STR);
		$stmt -> bindParam(":honbun",$honbun, PDO::PARAM_STR);
		$stmt -> execute();

//-------------------以下はCSVファイルを格納するためのフォルダ作成コード-------------

$files = "./base";		//コピー元のディレクトリのパス
		$path = "./dir$pageid";	//フォルダ作成
		if(mkdir($path)){
			echo "success!  ";
		}else{
			echo "failed";
		}
		//作成したディレクトリにコピーする
		if (!is_dir($path)) {
			mkdir($path);
		}
		
		if (is_dir($files)) {
			if ($dh = opendir($files)) {
				while (($file = readdir($dh)) !== false) {
					if ($file == "." || $file == "..") {
						continue;
					}
					if (is_dir($files . "/" . $file)) {
						dir_copy($files . "/" . $file, $path . "/" . $file);
					}
					else {
						copy($files . "/" . $file, $path . "/" . $file);
					}
				}
			closedir($dh);
			}
		}
	
//---------------------------------------------------------------------------
	?>

	<?php
		// ※2 メッセージ表示
		if (createNewPage( $filename, $template, $pagetitle, $honbun,$path)) {
			echo $filename. "の親ページを作成しました。";
		} else {
			echo "親ページの生成に失敗しました。";
		}
		} else {
			echo "親ページの内容を送信してください。";
		}

		// ※3 ページ生成関数 createNewPage()
		function createNewPage( $filename, $template, $pagetitle, $honbun,$path) {
		// ※4 テンプレートファイルの読み込み
		if ( ($contents = file_get_contents( $template)) == FALSE) { return false; }

			// タイトルと記事本文を挿入
			$contents = str_replace( "<%PAGETITLE>", $pagetitle, $contents);
			$contents = str_replace( "<%PAGECONTENTS>", $honbun, $contents);

			chdir("./$path"); //フォルダを開く

			// ※5 そこにファイル生成＆書き込み
			if ( ($handle = fopen( $filename, 'w')) == FALSE) { return false; }
				fwrite( $handle, $contents);
				fclose( $handle );
	
				return true;
			}
	?>

	<form>	
		<input type="button" onClick="location.href='<?php echo "./dir" . $pageid . "/" . $pageid . ".php" ?>'" value="親ページへ"/>
		<input type="button" onClick="location.href='javascript:history.back()'" value="戻る">

	</form> 
	</body>
</html>