<?php
	session_start();

	if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
		header('Location: post.php');
		exit();
	}

	require_once('../inc/config.php');
	require_once('../inc/functions.php');
	require_once('../inc/class-query.php');

	check_token();

	$image_name = upload_image('../upload/');

	$post = array(
		'title' => $_POST['title'],
		'category_id' => (int)$_POST['category_id'],
		'content' => $_POST['content'],
		'image_name' => $image_name
	);

	$query = new Query();
	$query->post_add($post);
	$query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>登録が完了しました。</title>
	<link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
	<main>
		<div class="l-container --ss">
			<h1 class="c-pageTitle">登録が完了しました。</h1>
			<a href="index.php" class="c-button c-buttonBack">投稿一覧へ</a>
		</div>
	</main>
</body>
</html>
