<?php
// XSS対策
function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

// CSRF対策 トークンの生成
function set_token() {
  if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
  }
}

// CSRF対策 トークンの確認
function check_token() {
  if (empty($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    echo '不正な投稿（トークンが一致しません。）';
    exit();
  }
}

/**
 * 画像アップロード
 * @param string $dir 画像ファイル保存先ディレクトリ
 */
function upload_image($dir) {
  $file_name = '';
  if( $_POST['post_image'] ) {
    $file_name = $_POST['post_image'];
  }
  if ( isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK ) {
		$file_type = exif_imagetype($_FILES['post_image']['tmp_name']);
		if ($file_type != IMAGETYPE_GIF && $file_type != IMAGETYPE_JPEG && $file_type != IMAGETYPE_PNG) {
			echo '画像は「gif」、「jpeg」、「png」形式で指定して下さい。';
			exit();
		}
		$file_name = date('YmdHis') . '-' . $_FILES['post_image']['name'];
		move_uploaded_file($_FILES['post_image']['tmp_name'], $dir . $file_name);
  }
  return $file_name;
}

/**
 * ページングに必要な情報を返す
 * @param integer $total 全記事数
 * @return array current_page=>現在のページ, prev_page=>前のページ, next_page=>次のページ
 */
function set_paging($total) {
  $page = 1;
  if ( !empty($_GET['page']) || isset($_GET['page']) || is_numeric($_GET['page']) ) {
    $page = $_GET['page'];
	}
  $current_page = max($page, 1);
  $current_page = min($page, $total);
  $prev_page = $current_page - 1;
  $next_page = $current_page + 1;
  return array(
    'current_page'  => $current_page,
    'prev_page'     => $prev_page,
    'next_page'     => $next_page
  );
}
