<?php
	if( empty($_GET['id']) || !isset($_GET['id']) || !is_numeric($_GET['id']) ) {
		header('Location: index.php');
		exit();
	}
	require_once('inc/config.php');
	require_once('inc/functions.php');
	require_once('inc/class-query.php');

	$query = new Query();
	$post = $query->get_post((int)$_GET['id']);
	$query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?php echo h($post['title']); ?></title>
	<link rel="stylesheet" href="assets/css/normalize.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<main>
		<div class="l-container --sm">
			<article class="p-postArticle">
				<?php if( !empty($post) ): ?>
				<header class="p-postHeader">
					<h1 class="p-postTitle"><?php echo h($post['title']); ?></h1>
					<p class="p-postCreated"><time class="p-postTime" datetime="<?php echo h($post['created']); ?>"><?php echo h(date('Y.m.d', strtotime($post['created']))); ?></time></p>
					<?php if($post['category_name']): ?>
					<ul class="p-postCategoryList">
						<li class="c-categoryLabel"><?php echo h($post['category_name']); ?></li>
					</ul>
					<?php endif; ?>
					<?php if( !empty($post['post_image']) ): ?>
						<img src="upload/<?php echo h($post['post_image']); ?>" alt="" width="800" class="p-postImage">
					<?php endif; ?>
				</header>
				<p class="p-postContent">
					<?php echo nl2br(h($post['content']), false); ?>
				</p>
				<?php endif; ?>
			</article>
			<a href="javascript:history.back()" class="c-button c-buttonBack">一覧に戻る</a>
		</div>
	</main>
</body>
</html>
