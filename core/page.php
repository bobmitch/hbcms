<?php
// guest, registered, author, editor, admin
defined('CMSPATH') or die; // prevent unauthorized access

class Page {
	public $id;
	public $title;
	public $alias;
	public $parent;
	public $controller;
	public $updated;



	public function __construct() {
		$this->id = false;
		$this->title = "";
		$this->alias = "";
		$this->parent = false;
		$this->created = date('Y-m-d H:i:s');
		$this->controller = false;
		$this->id = false;
	}

	public function create_new ($username, $password, $email, $groups=[], $state=0) {
		if ($username && $email && $password) {
			$hash = password_hash ($password, PASSWORD_DEFAULT);
			$query = "INSERT INTO users (username, email, password, state) VALUES (?,?,?,?)";
			CMS::Instance()->$pdo->prepare($query)->execute([$username,$email,$hash,$state]);
		}
		else {
			// TODO:throw
		}
	}
	// $pdo->prepare($sql)->execute([$name, $id]);
	public function get_all_pages() {
		//echo "<p>Getting all users...</p>";
		//$db = new db();
		//$db = CMS::$pdo;
		//$result = $db->pdo->query("select * from users")->fetchAll();
		$result = CMS::Instance()->pdo->query("select * from pages")->fetchAll();
		return $result;
	}

	public function load_from_post() {
		$this->username = CMS::getvar('username','USERNAME');
		$this->password = password_hash ($_POST['password'], PASSWORD_DEFAULT); 
		$this->email = CMS::getvar('email','EMAIL');
		if (!$this->email) {
			CMS::queue_message('Invalid email','warning');
			return false;
		}
		$this->registered = date('Y-m-d H:i:s');
		$this->id = false;
		return true;
		// todo: get groups too!
	}

	public function load_from_id($id) {
		$query = "select * from users where id=?";
		$db = new db();
		$stmt = $db->pdo->prepare($query);
		$stmt->execute(array($id));
		$result = $stmt->fetch();
		if ($result) {
			$this->username = $result->username;
			$this->password = $result->password;
			$this->created = $result->created;
			$this->groups = false; // TODO: get groups
			$this->email = $result->email;
			$this->id = $result->id;
			return true;
		}
		else {
			return false;
		}
	}

	public function check_password($password) {
		$query = "select password from users where id=?";
		$db = new db();
		$stmt = $db->pdo->prepare($query);
		$stmt->execute(array($this->id));
		$hash = $stmt->fetch();
		return password_verify($password, $hash->password);
	}

	public function load_from_username($username) {
		echo "<h5>Loading user object from db with username {$username}</h5>";
		$query = "select * from users where username=?";
		$db = new db();
		$stmt = $db->pdo->prepare($query);
		$stmt->execute(array($username));
		$result = $stmt->fetch();
		if ($result) {
			$this->username = $result->username;
			$this->password = $result->password;
			$this->created = $result->created;
			$this->groups = false; // TODO: get groups
			$this->email = $result->email;
			$this->id = $result->id;
			return true;
		}
		else {
			return false;
		}
	}

	public function save() {
		if ($this->id) {
			// update
			$query = "update users set username=?, password=?, email=? where id=?";
			$result = CMS::Instance()->pdo->prepare($query)->execute(array($this->username, $this->password, $this->email, $this->id));
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
			$query = "insert into users (username,email,password) values(?,?,?)";
			try {
				$result = CMS::Instance()->pdo->prepare($query)->execute(array($this->username, $this->email, $this->password));	
			}
			catch (PDOException $e) {
				CMS::Instance()->queue_message('Username and/or email already exists','danger',Config::$uripath.'/admin/users/new');
				if (Config::$debug) {
					echo "<code>" . $e->getMessage() . "</code>";
				}
				$result = false;
			}
			if ($result) {
				return true;
			}
			else {
				// todo - check for username/email already existing and clarify
				CMS::Instance()->queue_message('Unable to create user','danger',Config::$uripath.'/admin/users');
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