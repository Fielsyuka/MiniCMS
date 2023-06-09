<?php
	session_start();

	require_once('../inc/config.php');
	require_once('../inc/functions.php');
	require_once('../inc/class-query.php');

	set_token();

	$query = new Query(array(
		'limit' => 5
	));
	$total = $query->get_posts_count();
	$paging = set_paging($total);
	$posts = $query->get_all_posts($paging['current_page']);
	$categories = $query->get_all_categories();
	$query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>管理画面</title>
	<link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
	<main>
		<div class="l-container --lg">
			<h1 class="c-pageTitle">管理画面</h1>
			<section>
				<h2 class="c-titleLv2">投稿記事一覧</h2>
				<p class="mt56"><a href="post.php" class="c-button c-buttonPrimary">＋ 新しい記事を投稿する</a></p>
				<table class="p-adminTable mt32">
						<thead class="p-adminTable__head">
							<tr class="p-adminTable__row">
								<th>ID</th>
								<th>タイトル</th>
								<th>カテゴリID</th>
								<th>公開日</th>
								<th>更新日</th>
								<th>編集</th>
								<th>削除</th>
							</tr>
						</thead>
						<tbody class="p-adminTable__body">
							<?php if($posts): ?>
							<?php foreach($posts as $row) : ?>
							<tr class="p-adminTable__row">
								<td class="id"><?php echo h($row['id']); ?></td>
								<td class="title"><a href="edit.php?id=<?php echo h($row['id']); ?>" class="c-linkText"><?php echo h($row['title']); ?></a></td>
								<td class="cat_id"><?php echo h($row['category_id']); ?></td>
								<td class="created"><time datetime="<?php echo h($row['created']); ?>"><?php echo h(date('Y年m月d日', strtotime($row['created']))); ?></time></td>
								<td class="modified"><time datetime="<?php echo h($row['modified']); ?>"><?php echo h(date('Y年m月d日', strtotime($row['modified']))); ?></time></td>
								<td class="edit"><a href="edit.php?id=<?php echo h($row['id']); ?>" class="c-linkText">編集</a></td>
								<td class="delete">
									<form action="delete.php" method="post">
										<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
										<input type="hidden" name="id" value="<?php echo h($row['id']); ?>">
										<input type="submit" value="削除" class="c-button c-linkText">
									</form>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
				</table>
				<nav class="c-pagination">
					<h2 class="u-visuallyHidden">ページナビゲーション</h2>
					<ul class="c-pagination__list">
						<?php if ($paging['current_page'] > 1 ) : ?>
						<li class="c-pagination__prev"><a href="index.php?page=<?php echo h( $paging['prev_page'] ); ?>">前のページへ</a></li>
						<?php endif; ?>
						<?php if ( $paging['current_page'] < $total ) : ?>
						<li class="c-pagination__next"><a href="index.php?page=<?php echo h( $paging['next_page'] ); ?>">次のページへ</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</section>
			<section class="l-spacerSection">
				<h2 class="c-titleLv2">カテゴリ管理</h2>
				<div class="p-adminCat mt56">
					<form action="add_cat.php" method="post" class="p-adminCat__form">
						<dl class="p-adminPostForm">
							<dt class="p-adminPostForm_dt"><label for="category_name">新しくカテゴリを追加する</label></dt>
							<dd class="p-adminPostForm_dd">
								<input type="text" id="category_name" name="category_name" required>
							</dd>
						</dl>
						<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
						<p class="u-textRight"><input type="submit" value="＋ 登録" class="c-button c-buttonPrimary"></p>
					</form>
					<table class="p-adminTable p-adminCat__table">
							<thead class="p-adminTable__head">
								<tr class="p-adminTable__row">
									<th>カテゴリID</th>
									<th>カテゴリ名</th>
									<th>編集</th>
									<th>削除</th>
								</tr>
							</thead>
							<tbody class="p-adminTable__body">
								<?php if($categories): ?>
								<?php foreach($categories as $cat) : ?>
								<tr class="p-adminTable__row">
									<td class="cat_id"><?php echo h($cat['id']); ?></td>
									<td class="cat_name"><?php echo h($cat['category_name']); ?></td>
									<td class="edit"><a href="edit_cat.php?id=<?php echo h($cat['id']); ?>" class="c-linkText">編集</a></td>
									<td class="delete">
										<form action="delete_cat.php" method="post">
											<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
											<input type="hidden" name="category_id" value="<?php echo h($cat['id']); ?>">
											<input type="submit" value="削除" class="c-button c-linkText">
										</form>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
					</table>
				</div>
			</section>
		</div>
	</main>
</body>
</html>
