<?php

defined('CMSPATH') or die; // prevent unauthorized access

class Content {
	public $id;
	public $title;
	public $state;
	public $description;
	public $configuration;
	public $updated;
	public $content_type;

	public function __construct() {
		$this->id = false;
		$this->title = "";
		$this->description = "";
		$this->state = 1;
		$this->updated = date('Y-m-d H:i:s');
		$this->content_type = 0;
	}

	public function get_field($field_name) {
		$query = "select content from content_fields where content_id=? and name=?";
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($this->id, $field_name));
		$value = $stmt->fetch();
		if ($value) {
			return $value->content;
		}
		else {
			return null;
		}
	}

	public function load($id) {
		$info = CMS::Instance()->pdo->query('select * from content where id=' . $id)->fetch();
		$this->id = $info->id;
		$this->title = $info->title;
		$this->state = $info->state;
		$this->note = $info->note;
		$this->alias = $info->alias;
		$this->start = $info->start;
		$this->end = $info->end;
		$this->content_type = $info->content_type;
		$this->content_location = $this->get_content_location($this->content_type);
	}

	public function save($required_details_form, $content_form) {
		// update this object with submitted and validated form info
		$this->title = $required_details_form->get_field_by_name('title')->default;
		$this->state = $required_details_form->get_field_by_name('state')->default;
		$this->note = $required_details_form->get_field_by_name('note')->default;
		$this->alias = $required_details_form->get_field_by_name('alias')->default;
		$this->filter = $required_details_form->get_field_by_name('start')->default;
		$this->image = $required_details_form->get_field_by_name('end')->default;

		

		if ($this->id) {
			// update
			$query = "update content set state=?,  title=?, alias=?, note=?, start=?, end=? where id=?";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$params = array($this->state, $this->title, $this->alias, $this->note, $this->start, $this->end, $this->id) ;
			$required_result = $stmt->execute( $params );
		}
		else {
			// new
			$query = "insert into content (state,public,title,alias,note,start,end) values(?,?,?,?,?,?)";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$params = array($this->state, $this->public, $this->title, $this->alias, $this->note, $this->start, $this->end);
			$required_result = $stmt->execute( $params );
		}
		if (!$required_result) {
			// TODO: specific message for new/edit etc
			CMS::Instance()->queue_message('Failed to save content','danger', $_SERVER['HTTP_REFERER']);
		}
		// now save fields
		/* CMS::pprint_r ($this);
		CMS::pprint_r ($content_form); */
		// first remove old field data if any exists
		$query = "delete from content_fields where content_id=?";
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($this->id));
		$error_text="";
		foreach ($content_form->fields as $field) {
			// insert field info
			// TODO: handle arrays
			$query = "insert into content_fields (content_id, name, field_type, content) values (?,?,?,?)";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$field_data = array($this->id, $field->name, $field->type, $field->default);
			$result = $stmt->execute($field_data);
			if (!$result) {
				$error_text .= "Error saving: " . $field->name . " ";
			}
		}
		if ($error_text) {
			CMS::Instance()->queue_message($error_text,'danger', $_SERVER['HTTP_REFERER']);
		}
		else {
			CMS::Instance()->queue_message('Saved content','success', $_SERVER['HTTP_REFERER']);
		}
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

	public static function get_content_type_for_view ($view_id) {
		$stmt = CMS::Instance()->pdo->prepare("select content_type_id from content_views where id=?");
		$stmt->execute(array($view_id));
		$result = $stmt->fetch();
		return $result->content_type_id;
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