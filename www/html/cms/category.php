<?php
	if ( empty($_GET['id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
  }

  require_once('inc/config.php');
  require_once('inc/functions.php');
  require_once('inc/class-query.php');

  $query = new Query(array(
    'limit'       => 2
  ));
  $total = $query->get_posts_count((int)$_GET['id']);
  $paging = set_paging($total);

  $posts = $query->get_all_posts($paging['current_page'], (int)$_GET['id']);
  $categories = $query->get_all_categories(array(
    'has_post' => true
  ));
  $query->exit();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新着記事一覧</title>
  <link rel="stylesheet" href="assets/css/normalize.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main>
    <div class="l-container --ss">
      <h1 class="c-pageTitle">新着情報</h1>
      <ul class="p-categoryList">
        <li class="p-categoryList__item"><a href="index.php">すべて</a></li>
        <?php foreach($categories as $category): ?>
          <?php if($category['id'] === $_GET['id']): ?>
          <li class="p-categoryList__item current"><?php echo h($category['category_name']); ?></li>
          <?php else: ?>
          <li class="p-categoryList__item"><a href="category.php?id=<?php echo h($category['id']); ?>"><?php echo h($category['category_name']); ?></a></li>
          <?php endif; ?>
        <?php endforeach; ?>
        </ul>
      <?php if($posts): ?>
      <?php foreach($posts as $post): ?>
      <dl class="p-archiveList">
        <dt class="p-archiveList__dt"><time datetime="<?php echo h($post['created']); ?>"><?php echo h(date('Y.m.d', strtotime($post['created']))); ?></time></dt>
        <dd class="p-archiveList__dd">
          <p class="p-archiveList__category"><span class="c-categoryLabel"><?php echo h($post['category_name']); ?></span></p>
          <a href="detail.php?id=<?php echo h($post['id']); ?>" class="p-archiveList__link"><?php echo h($post['title']);?></a>
        </dd>
      </dl>
      <?php endforeach; ?>
      <nav class="c-pagination">
        <h2 class="u-visuallyHidden">ページナビゲーション</h2>
          <ul class="c-pagination__list">
        <?php if ($paging['current_page'] > 1 ) : ?>
          <li class="c-pagination__prev"><a href="category.php?id=<?php echo h($_GET['id']); ?>&page=<?php echo h( $paging['prev_page'] ); ?>">前のページへ</a></li>
        <?php endif; ?>
        <?php if ( $paging['current_page'] < $total ) : ?>
          <li class="c-pagination__next"><a href="category.php?id=<?php echo h($_GET['id']); ?>&page=<?php echo h( $paging['next_page'] ); ?>">次のページへ</a></li>
        <?php endif; ?>
        </ul>
      </nav>
      <?php else: ?>
        <p>まだ投稿がありません。</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
