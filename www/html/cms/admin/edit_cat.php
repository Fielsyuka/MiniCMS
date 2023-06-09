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
  $category = $query->get_category((int)$_GET['id']);
  $query->exit();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カテゴリの編集</title>
	<link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <main>
    <div class="l-container --sm">
      <h1 class="c-pageTitle">カテゴリの編集</h1>
      <form action="update_cat.php" method="post" class="p-adminBox">
        <dl class="p-adminPostForm">
          <dt class="p-adminPostForm_dt"><label for="category_name">カテゴリ名</label></dt>
          <dd class="p-adminPostForm_dd">
          <input type="text" id="category_name" name="category_name" value="<?php echo h($category['category_name']); ?>">
          </dd>
        </dl>
        <input type="hidden" name="id" value="<?php echo h($category['id']); ?>">
        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
        <p class="mt40 u-textCenter"><input type="submit" value="変更" class="c-button c-buttonPrimary"></p>
      </form>
    </div>
  </main>
</body>
</html>
