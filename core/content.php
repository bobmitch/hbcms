<?php

defined('CMSPATH') or die; // prevent unauthorized access

class Content {
	public $id;
	public $title;
	public $state;
	public $description;
	public $configuration;
	public $updated;



	public function __construct() {
		$this->id = false;
		$this->title = "";
		$this->description = "";
		$this->state = 1;
		$this->updated = date('Y-m-d H:i:s');
	}


	// $pdo->prepare($sql)->execute([$name, $id]);
	public static function get_all_content_types() {
		//echo "<p>Getting all users...</p>";
		//$db = new db();
		//$db = CMS::$pdo;
		//$result = $db->pdo->query("select * from users")->fetchAll();
		$result = CMS::Instance()->pdo->query("select * from content_types where state > 0 order by id ASC")->fetchAll();
		return $result;
	}
	
	public static function get_content_type_title($content_type) {
		if (!$content_type) {
			return false;
		}
		$stmt = CMS::Instance()->pdo->prepare("select title from content_types where id=?");
		$stmt->execute(array($content_type));
		$result = $stmt->fetch();
		if ($result) {
			return $result->title;
		}
		else {
			return false;
		}
	}

	public static function get_content_location($content_type_id) {
		$stmt = CMS::Instance()->pdo->prepare("select controller_location from content_types where id=?");
		$stmt->execute(array($content_type_id));
		$result = $stmt->fetch();
		return $result->controller_location;
	}

	public static function get_view_location($view_id) {
		$stmt = CMS::Instance()->pdo->prepare("select location from content_views where id=?");
		$stmt->execute(array($view_id));
		$result = $stmt->fetch();
		return $result->location;
	}

	public static function get_view_title($view_id) {
		if (!$view_id) {
			return false;
		}
		$stmt = CMS::Instance()->pdo->prepare("select title from content_views where id=?");
		$stmt->execute(array($view_id));
		$result = $stmt->fetch();
		if ($result) {
			return $result->title;
		}
		else {
			return false;
		}
	}
	
	public static function get_all_content() {
		//echo "<p>Getting all users...</p>";
		//$db = new db();
		//$db = CMS::$pdo;
		//$result = $db->pdo->query("select * from users")->fetchAll();
		$result = CMS::Instance()->pdo->query("select * from content order by id ASC")->fetchAll();
		return $result;
	}

}