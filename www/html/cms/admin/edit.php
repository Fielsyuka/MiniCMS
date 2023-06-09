<?php
  session_start();

  if ( empty($_GET['id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
  }

  require_once('../inc/config.php');
  require_once('../inc/functions.php');
  require_once('../inc/class-query.php');

  set_token();

  $query = new Query();
  $post = $query->get_post((int)$_GET['id']);
  $categories = $query->get_all_categories();
  $query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>記事の更新</title>
  <link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <main>
    <div class="l-container --sm">
      <h1 class="c-pageTitle">記事の更新</h1>
      <form action="update.php" method="post" enctype="multipart/form-data" class="p-adminBox">
        <dl class="p-adminPostForm">
          <dt class="p-adminPostForm_dt"><label for="title">タイトル</label></dt>
          <dd class="p-adminPostForm_dd">
            <input type="text" id="title" name="title" value="<?php echo h($post['title']); ?>" required>
          </dd>
          <dt class="p-adminPostForm_dt"><label for="category_id">カテゴリー</label></dt>
          <dd class="p-adminPostForm_dd">
              <select name="category_id" id="category_id">
                <?php  foreach($categories as $cat): ?>
                  <?php $cat['id'] === $post['category_id'] ? $current_cat = true : $current_cat = false; ?>
                <option value="<?php echo h($cat['id']); ?>"<?php if($current_cat){echo 'selected'; } ?>><?php echo h($cat['category_name']); ?></option>
                <?php endforeach; ?>
              </select>
          </dd>
          <dt class="p-adminPostForm_dt"><label for="content">記事の内容</label></dt>
          <dd class="p-adminPostForm_dd">
            <textarea name="content" id="content" cols="30" rows="10" required><?php echo h($post['content']); ?></textarea>
          </dd>
            <dt class="p-adminPostForm_dt"><label for="post_image">画像</label></dt>
            <dd class="p-adminPostForm_dd">
            <?php if( !empty($post['post_image']) ) : ?>
              <img src="../upload/<?php echo h($post['post_image']) ?>" alt="" width="200">
              <input type="hidden" name="post_image" value="<?php echo h($post['post_image']); ?>">
            <?php endif; ?>
              <input type="file" id="post_image" name="post_image">
            </dd>
        </dl>
          <input type="hidden" name="id" value="<?php echo h($post['id']); ?>">
          <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
        <p class="mt40 u-textCenter"><input type="submit" value="変更" class="c-button c-buttonPrimary"></p>
      </form>
    </div>
  </main>
</body>
</html>
