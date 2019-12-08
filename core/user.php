<?php
// guest, registered, author, editor, admin
defined('CMSPATH') or die; // prevent unauthorized access

class User {
	public $groups;
	public $username;
	public $password;
	public $email;



	public function __construct() {
		$this->email = false;
		$this->password = false;
		$this->groups = array();
		$this->username = 'guest';
		$this->registered = date('Y-m-d H:i:s');
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
	public function get_all_users() {
		//echo "<p>Getting all users...</p>";
		//$db = new db();
		//$db = CMS::$pdo;
		//$result = $db->pdo->query("select * from users")->fetchAll();
		$result = CMS::Instance()->pdo->query("select * from users")->fetchAll();
		return $result;
	}

	public function is_member_of ($group_value) {
		$query = "select id from groups where value=? and id in (select group_id from user_groups where user_id=?)";
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($group_value, $this->id));
		$result = $stmt->fetch();
		if ($result) {
			if ($result->id) {
				return true;
			}
		}
		return false;
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

	
	public static function get_username_by_id($id) {
		$stmt = CMS::Instance()->pdo->prepare("select username from users where id=?");
		$stmt->execute(array($id));
		$result = $stmt->fetch()->username;
		return $result;
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






}