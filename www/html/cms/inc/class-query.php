<?php
/**
 * クエリを実行してDBデータ等を格納するクラス
 */
class Query {
	private $dbh;
	private $stmt;
	private $settings;

	function __construct($settings = ['limit' => 5]) {
		try {
			$dsn = 'mysql:host='. HOST .';dbname='. DB_NAME . ';charset=utf8';
			$this->dbh = new PDO($dsn, DB_USER, DB_PASSWORD);
			$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'データベース接続エラーが発生しました:' . h($e->getMessage());
			exit();
		}
		if( !is_null($settings) ) {
			$this->settings = $settings;
		}
	}

	/**
	 * 全て又は指定したカテゴリの投稿数を取得
	 * @param integer|null $category_id
	 */
	public function get_posts_count($category_id = NULL) {
		try {
			if( !isset($category_id) ) {
				$sql = 'SELECT COUNT(*) AS total FROM posts';
				$posts_count = $this->selectOne($sql);
			} else {
				$sql = 'SELECT COUNT(*) AS total FROM posts WHERE category_id=?';
				$posts_count = $this->selectOne($sql, [
					[$category_id, PDO::PARAM_INT]
				]);
			}
			return max(ceil($posts_count['total'] / $this->settings['limit']), 1);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 全ての投稿データを取得
	 * @param integer $page ページ番号
	 * @param integer|null $category_id
	 */
	public function get_all_posts($page, $category_id = NULL) {
		$start = ($page - 1) * $this->settings['limit'];
		try {
			if( is_null($category_id) ) {
				$sql = 'SELECT p.*, c.category_name FROM posts AS p LEFT JOIN categories AS c ON p.category_id = c.id ORDER BY p.created DESC LIMIT ?, ?';
				$posts = $this->selectAll($sql, [
					[$start, PDO::PARAM_INT],
					[$this->settings['limit'], PDO::PARAM_INT]
				]);
			} else {
				$sql = 'SELECT p.*, c.category_name FROM posts AS p LEFT JOIN categories AS c ON p.category_id = c.id WHERE category_id=? ORDER BY p.created DESC LIMIT ?, ?';
				$posts = $this->selectAll($sql, [
					[$category_id, PDO::PARAM_INT],
					[$start, PDO::PARAM_INT],
					[$this->settings['limit'], PDO::PARAM_INT]
				]);
			}
			return $posts;
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 投稿詳細のデータを取得
	 */
	public function get_post($post_id) {
		try {
			$sql = 'SELECT p.*, c.category_name FROM posts AS p LEFT JOIN categories AS c ON p.category_id = c.id WHERE p.id=?';
			$post = $this->selectOne($sql, [
				[(int)$post_id, PDO::PARAM_INT]
			]);
			return $post;
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 全てのカテゴリを取得
	 */
	public function get_all_categories($array = ['has_post' => false]) {
		try {
			if($array['has_post']) {
				$sql = 'SELECT DISTINCT c.id, c.category_name FROM categories AS c JOIN posts AS p ON c.id = p.category_id';
			} else {
				$sql = 'SELECT * FROM categories';
			}
			$all_categories = $this->selectAll($sql);
			return $all_categories;
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 指定したカテゴリを取得
	 * @param integer $category_id
	 */
	public function get_category($category_id) {
		try {
			$sql = 'SELECT * FROM categories WHERE id=?';
			$category = $this->selectOne($sql, [
				[$category_id, PDO::PARAM_INT]
			]);
			return $category;
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 記事の追加
	 * @param array $post
	 */
	public function post_add($post) {
		try {
			$sql = 'INSERT INTO posts (title, category_id, content, post_image, created) values(?, ?, ?, ?, now())';
			$this->execute($sql,[
				[$post['title'], PDO::PARAM_STR],
				[$post['category_id'], PDO::PARAM_INT],
				[$post['content'], PDO::PARAM_STR],
				[$post['image_name'], PDO::PARAM_STR]
			]);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 記事の更新
	 */
	public function post_update($post = []) {
		try {
			$sql = 'UPDATE posts SET title=?, content=?, category_id=?, post_image=? WHERE id=?';
			$this->execute($sql,[
				[$post['title'], PDO::PARAM_STR],
				[$post['content'], PDO::PARAM_STR],
				[$post['category_id'], PDO::PARAM_INT],
				[$post['image_name'], PDO::PARAM_STR],
				[$post['post_id'], PDO::PARAM_STR]
			]);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * 記事の削除
	 * @param integer $post_id
	 */
	public function post_delete($post_id) {
		try {
			$sql = 'DELETE FROM posts WHERE id=?';
			$this->execute($sql, [
				[$post_id, PDO::PARAM_INT]
			]);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

		/**
	 * カテゴリの追加
	 * @param string $category_name
	 */
	public function category_add($category_name) {
		try {
			$sql = 'INSERT INTO categories (category_name) VALUES(?)';
			$this->execute($sql,[
				[$category_name, PDO::PARAM_STR],
			]);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

		/**
	 * カテゴリの更新
	 * @param array $category
	 */
	public function category_update($category) {
		try {
			$sql = 'UPDATE categories SET category_name=? WHERE id=?';
			$this->execute($sql,[
				[$category['name'], PDO::PARAM_STR],
				[$category['id'], PDO::PARAM_INT]
			]);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * カテゴリの削除
	 * @param integer $category_id
	 */
	public function category_delete($category_id) {
		try {
			$sql = 'DELETE FROM categories WHERE id=?';
			$this->execute($sql, [
				[$category_id, PDO::PARAM_INT]
			]);
		} catch (PDOException $e) {
			echo 'エラーが発生しました:' . h($e->getMessage());
			exit();
		}
	}

	/**
	 * Prepared Statement
	 * @param string $sql
	 * @param array $params
	 * @return void
	 */
	public function execute($sql = '', $params = []){
		if(!empty($params)) {
			$this->stmt = $this->dbh->prepare($sql);
			foreach($params as $index =>$param) {
				$this->stmt->bindValue($index + 1, $param[0], $param[1]);
			}
			$this->stmt->execute();
		} else {
			$this->stmt = $this->dbh->query($sql);
		}
	}

	/**
	 * セレクト文 複数レコード
	 * @param string $sql
	 * @param array $params
	 * @return array
	 */
	public function selectAll($sql = '', $params = []){
		if(!empty($params)) {
			$this->stmt = $this->dbh->prepare($sql);
			foreach($params as $index =>$param) {
				$this->stmt->bindValue($index + 1, $param[0], $param[1]);
			}
			$this->stmt->execute();
		} else {
			$this->stmt = $this->dbh->query($sql);
		}
		return $this->stmt->fetchAll();
	}

	/**
	 * セレクト文 単一レコード
	 * @param string $sql
	 * @param array $params
	 * @return array
	 */
	public function selectOne($sql = '', $params = []){
		if(!empty($params)) {
			$this->stmt = $this->dbh->prepare($sql);
			foreach($params as $index =>$param) {
				$this->stmt->bindValue($index + 1, $param[0], $param[1]);
			}
			$this->stmt->execute();
		} else {
			$this->stmt = $this->dbh->query($sql);
		}
		return $this->stmt->fetch();
	}

	/**
	 * 終了
	 * @return void
	 */
	public function exit(){
		$dbh = null;
	}
}
