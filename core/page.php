<?php
// guest, registered, author, editor, admin
defined('CMSPATH') or die; // prevent unauthorized access

class Page {
	public $id;
	public $state;
	public $title;
	public $alias;
	public $template;
	public $parent;
	public $content_type;
	public $view;
	public $updated;
	public $view_configuration;



	public function __construct() {
		$this->id = 0;
		$this->state = 1;
		$this->title = "";
		$this->alias = "";
		$this->template = 1;
		$this->parent = false;
		$this->updated = date('Y-m-d H:i:s');
		$this->content_type = null;
		$this->view = null;
		$this->view_configuration = false;
	}

	
	// $pdo->prepare($sql)->execute([$name, $id]);
	public static function get_all_pages() {
		//echo "<p>Getting all users...</p>";
		//$db = new db();
		//$db = CMS::$pdo;
		//$result = $db->pdo->query("select * from users")->fetchAll();
		$result = CMS::Instance()->pdo->query("select * from pages")->fetchAll();
		return $result;
	}

	public static function get_all_pages_by_depth($parent=-1, $depth=-1) {
		$depth = $depth+1;
		$result=array();
		$stmt = CMS::Instance()->pdo->prepare("select * from pages where parent=?");
		$stmt->execute(array($parent));
		$children = $stmt->fetchAll();
		foreach ($children as $child) {
			$child->depth = $depth;
			$result[] = $child;
			$result = array_merge ($result, Page::get_all_pages_by_depth($child->id, $depth));
		}
		return $result;
	}


	public static function get_pages_from_id_array ($id_array) {
		if (is_array($id_array)) {
			$in_string = implode(',',$id_array);
			$query = "select * from pages where id in ({$in_string})";
			return  CMS::Instance()->pdo->query($query)->fetchAll();
		}
		else {
			CMS::Instance()->queue_message('Expected array in function get_pages_from_id_array', 'danger', Config::$uripath . "/admin");
		}
	}

	public function load_from_post() {
		$this->title = CMS::getvar('title','TEXT');
		$this->state = CMS::getvar('state','NUM');
		if (!$this->state) {
			$this->state = 1;
		}
		$this->template = CMS::getvar('template','NUM');
		$this->alias = CMS::getvar('alias','TEXT');
		if (!$this->alias) {
			$this->alias = CMS::stringURLSafe($this->title);
		}
		$this->parent = CMS::getvar('parent','NUM');
		$this->content_type = CMS::getvar('content_type','NUM');
		$this->view = CMS::getvar('content_type_controller_view','NUM');
		$this->view_configuration = CMS::getvar('view_options','ARRAYTOJSON');
		
		$this->id = CMS::getvar('id','NUM');

		return true;
	}

	public function load_from_id($id) {
		$query = "select * from pages where id=?";
		//$db = new db();
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($id));
		$result = $stmt->fetch();
		if ($result) {
			$this->id = $result->id;
			$this->state = $result->state;
			$this->title = $result->title;
			$this->alias = $result->alias;
			$this->template = $result->template;
			$this->parent = $result->parent;
			$this->updated = $result->updated;
			$this->content_type = $result->content_type;
			$this->view = $result->content_view;
			$this->view_configuration = $result->content_view_configuration;
			return true;
		}
		else {
			return false;
		}
	}


	public function load_from_alias($alias) {
		$query = "select * from pages where alias=?";
		//$db = new db();
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($alias));
		$result = $stmt->fetch();
		if ($result) {
			$this->id = $result->id;
			$this->state = $result->state;
			$this->title = $result->title;
			$this->alias = $result->alias;
			$this->template = $result->template;
			$this->parent = $result->parent;
			$this->updated = $result->updated;
			$this->content_type = $result->content_type;
			$this->view = $result->content_view;
			$this->view_configuration = $result->content_view_configuration;
			return true;
		}
		else {
			return false;
		}
	}



	public function save() {
		if ($this->id) {
			// update
			$query = "update pages set state=?, title=?, alias=?, content_type=?, content_view=?, parent=?, template=?, content_view_configuration=? where id=?";
			$result = CMS::Instance()->pdo->prepare($query)->execute(array(
				$this->state, 
				$this->title, 
				$this->alias, 
				$this->content_type,
				is_numeric($this->view) ? $this->view : NULL,
				$this->parent,
				$this->template,
				$this->view_configuration,
				$this->id
			));
			if ($result) {
				// saved ok
				return true;
			}
			else {
				if (Config::$debug) {
					echo "<code>" . $e->getMessage() . "</code>";
					exit(0);
				}
				return false;
			}
		}
		else {
			// insert new
			$query = "insert into pages (state, title, alias, content_type, content_view, parent, template, content_view_configuration) values(?,?,?,?,?,?,?,?)";
			try {
				$stmt = CMS::Instance()->pdo->prepare($query);
				$result = $stmt->execute(array(
					$this->state, 
					$this->title, 
					$this->alias, 
					$this->content_type,
					is_numeric($this->view) ? $this->view : NULL,
					$this->parent,
					$this->template,
					$this->view_configuration
				));	
			}
			catch (PDOException $e) {
				//CMS::Instance()->queue_message('Error saving page','danger',Config::$uripath.'/admin/pages/');
				if (Config::$debug) {
					CMS::Instance()->queue_message('Error saving page: ' . $e->getMessage(),'danger',Config::$uripath.'/admin/pages/');
					//echo "<code>" . $e->getMessage() . "</code>";
				}
				$result = false;
				exit(0);
			}
			if ($result) {
				return true;
			}
			else {
				// todo - check for username/email already existing and clarify
				CMS::Instance()->queue_message('Unable to create page.' . $query ,'danger',Config::$uripath.'/admin/pages');
				return false;
			}
		}
	}

	public function get_all_groups() {
		//echo "<p>Getting all users...</p>";
		$result = CMS::Instance()->pdo->query("select * from groups")->fetchAll();
		return $result;
	}

	public function has_access($a,$b) {
		// function returns true if $a has access to $b
		$levels = unserialize(USERLEVELS);
		return ($levels[$a]>=$levels[$b]);
	}

	public function is_admin() {
		if ($this->level=='admin') {
			return true;
		}
		else {
			return false;
		}
	}

	public function is_editor() {
		if ($this->has_access($this->level,'editor')) {
			return true;
		}
	}

	public function set_level($level) {
		$this->level = $level;
	}

	public function get_level() {
		return $this->level;
	}


}