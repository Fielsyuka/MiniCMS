<?php
  session_start();

  if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
    header('Location: index.php');
    exit();
  }

  require_once('../inc/config.php');
  require_once('../inc/functions.php');
  require_once('../inc/class-query.php');

  check_token();

  $query = new Query();
  $query->category_delete((int)$_POST['category_id']);
  $query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カテゴリの削除</title>
  <link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
	<main>
		<div class="l-container --ss">
			<h1 class="c-pageTitle">カテゴリを削除しました。</h1>
			<a href="index.php" class="c-button c-buttonBack">管理画面へ</a>
		</div>
	</main>
</body>
</html>
