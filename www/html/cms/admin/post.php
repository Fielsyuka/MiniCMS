<?php
	session_start();

	require_once('../inc/config.php');
	require_once('../inc/functions.php');
	require_once('../inc/class-query.php');

	set_token();

	$query = new Query();
  $categories = $query->get_all_categories();
  $query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>投稿フォーム</title>
	<link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
	<header></header>
	<main>
		<div class="l-container --sm">
			<h1 class="c-pageTitle">投稿フォーム</h1>
			<form action="add.php" method="post" enctype="multipart/form-data" class="p-adminBox">
				<dl class="p-adminPostForm">
					<dt class="p-adminPostForm_dt"><label for="title">記事のタイトル</label></dt>
					<dd class="p-adminPostForm_dd">
						<input type="text" id="title" name="title" required>
					</dd>
					<dt class="p-adminPostForm_dt"><label for="category_id">カテゴリー</label></dt>
					<dd class="p-adminPostForm_dd">
					<select name="category_id" id="category_id">
						<?php  foreach($categories as $cat): ?>
						<option value="<?php echo h($cat['id']); ?>"><?php echo h($cat['category_name']); ?></option>
						<?php endforeach; ?>
					</select>
					</dd>
					<dt class="p-adminPostForm_dt"><label for="content">記事の内容</label></dt>
					<dd class="p-adminPostForm_dd">
						<textarea name="content" id="content" cols="30" rows="10" required></textarea>
					</dd>
					<dt class="p-adminPostForm_dt"><label for="post_image">画像</label></dt>
					<dd class="p-adminPostForm_dd">
						<input type="file" id="post_image" name="post_image">
					</dd>
				</dl>
				<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
				<p class="mt40 u-textCenter"><input type="submit" value="投稿" class="c-button c-buttonPrimary"></p>
			</form>
		</div>
	</main>
</body>
</html>
