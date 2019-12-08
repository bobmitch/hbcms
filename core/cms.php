<?php
defined('CMSPATH') or die; // prevent unauthorized access



// load config

require_once (CMSPATH . "/config.php");
if (Config::$debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}




final class CMS {
	public $domain;
	public $pdo;
	private $user;
	public $uri_segments;
	public $markup; // rendered html for current content item/page
	public $messages ;
	private static $instance = null;

	/* protected function __construct() {}
    protected function __clone() {}
    protected function __wakeup() {} */

	// singleton generator

	

	public final static function Instance(){
		
		if (self::$instance === null) {
			//if (Config::$debug) echo "<h3>Making new CMS instance</h3>";
			/* 
			echo "<pre>";
			print_r (debug_backtrace());
			echo "</pre>"; 
			echo "<hr>"; */
			//$inst = new CMS();
			self::$instance = new CMS();
		}
		return self::$instance;
	}

/* 	public function get_domain() {
		return $this->domain;
	}
	public function get_uri_segments() {
		return $this->uri_segments;
	} */

	


	static public function getvar($val, $filter="RAW") {
		if (isset($_GET[$val])) {
			$foo = $_GET[$val];
		}
		elseif (isset($_POST[$val])) {
			$foo = $_POST[$val];
		}
		else {
			//echo "<code>Var " . $val . " not found</code>";
			return NULL;
		}
		if ($filter=="RAW") {
			return $foo;
		}
		elseif ($filter=="USERNAME"||$filter=="TEXT") {
			return filter_var($foo, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		}
		elseif ($filter=="EMAIL") {
			return filter_var($foo, FILTER_VALIDATE_EMAIL);
		}
		elseif ($filter=="ARRAYTOJSON") {
			if (!is_array($foo)) {
				CMS::Instance()->queue_message('Cannot convert non-array to json in ARRAYTOJSON','danger',Config::$uripath . '/admin/pages');
				//echo "<h5>Variable is not array, cannot perform ARRAYTOJSON filter</h5>";
				return false;
			}
			$json = json_encode($foo);
			return $json;
		}
		elseif ($filter=="NUM"||$filter=="NUMBER"||$filter=="NUMERIC") {
			return filter_var($foo, FILTER_SANITIZE_NUMBER_INT);
		}
		else {
			return $foo;
		}
	}

	static public function stringURLSafe($string)
    {
        //remove any '-' from the string they will be used as concatonater
        $str = str_replace('-', ' ', $string);
        $str = str_replace('_', ' ', $string);

        //$lang =& JFactory::getLanguage();
        //$str = $lang->transliterate($str);

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);

        // lowercase and trim
        $str = trim(strtolower($str));
        return $str;
    }

	public function show_error($text) {
		echo "<div style='height:100vh; width:100%; display:flex; align-items:center; justify-content:center;'>";
		echo "<h1>{$text}</h1>";
		echo "</div>";
		exit(0);
	}

	private function __construct() {

		// setup domain
		if (Config::$domain=='auto') {
			$this->domain = $_SERVER['HTTP_HOST'];
		}
		else {
			$this->domain = Config::$domain;
		}

		// session 
		if(session_status() == PHP_SESSION_NONE){
			session_start();
		}

		/* session_destroy();
		$_SESSION = array(); */

		// db
		$dsn = "mysql:host=" . Config::$dbhost . ";dbname=" . Config::$dbname . ";charset=" . Config::$dbchar;
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			$this->pdo = new PDO($dsn, Config::$dbuser, Config::$dbpass, $options);
		} catch (\PDOException $e) {
			if (Config::$debug) {
				throw new \PDOException($e->getMessage(), (int)$e->getCode());
			}
			else {
				$this->show_error("Failed to connect to database: " . Config::$dbname);
			}
		}

		// messages
		$this->messages = new Messages();

		// handle user 
		$this->user = new User(); 
		if (s::get('user_id')) {
			$this->user->load_from_id(s::get('user_id'));
		} 

		// routing
		// first strip base uri path (from config) out of path
		$request = $_SERVER['REQUEST_URI'];
		$to_remove = Config::$uripath;
		if (ADMINPATH) {
			$to_remove .= "/admin/";
		}
		$request = str_ireplace($to_remove, "", $request);
		// split into array of segments
		$this->uri_segments = preg_split('@/@', parse_url($request, PHP_URL_PATH), NULL, PREG_SPLIT_NO_EMPTY);

		
	}

	private function showinfo() {
		echo "<style>#cmsinfo {border:1px solid black;box-shadow:0 0 10px rgba(0,0,0,0.5);margin:1rem;} #cmsinfo p, #cmsinfo pre {font-size:1rem; font-family:sans-serif;}</style>";
		echo "<div id='info'>";
		echo "<p>Domain: {$this->domain}</p>";
		echo "<p>Base Path (subfolder): " . Config::$uripath . "</p>";
		echo "<p>CMSPATH: " . CMSPATH . "</p>";
		echo "<p>ADMINPATH: " . ADMINPATH . "</p>";
		echo "<p>Default template: " . Config::$template . "</p>";
		echo "<p>User:<p>";
		$this->pprint_r($this->user);
		echo "<p>Segments:</p>";
		$this->pprint_r($this->uri_segments);
		echo "<p>Page:</p>";
		$this->pprint_r($this->page);
		echo "</div>";
		echo "<p>DB:</p>";
		$this->pprint_r($this->pdo);
		echo "<h1>Session:</h1>";
		echo "<code>"; $this->pprint_r ($_SESSION); echo "</code>";
		echo "<h1>System Ready</h1>";
	}


	public function queue_message($msg, $type='success', $redirect=null) {
		$this->messages->add($type,$msg, $redirect);
	}

	public function display_messages() {
		$this->messages->display();
	}

	public function has_messages() {
		return false;
		return ($this->messages->hasMessages());
	}

	public static function pprint_r ($o) {
		echo "<pre>";
		print_r ($o);
		echo "</pre>";
	}





	public function get_controller() {
		if (ADMINPATH) {
			// works different here boys and girls
			// controller name is first part of segment
			// todo: lookup in db first? make sure it's installed?
			if ($this->uri_segments) {
				return $this->uri_segments[0];
			}
			else {
				return false;
			}
		}
		else {
			// front end controllers
			// first determine page
			// look for deepest matching alias - once found, that page is our controller
			// if final matching alias is empty, show home
			// FOR NOW JUST DO HOME PAGE
			CMS::Instance()->page = new Page();
			CMS::Instance()->page->load_from_alias('home');
			if (CMS::Instance()->page->controller) {
				// do controller things
				return true;
			}
			else {
				return false;
			}
		}
	}

	public function render_controller() {
		// determine controller (if any)
		$controller = $this->get_controller();
		if ($controller) {
			//echo "<h4>Controller: {$controller}</h4>";
			include_once (CURPATH . "/controllers/" . $controller . "/controller.php");
		}
		else {
			if (Config::$debug) {
				echo "<h5>No controller found for URL. (normal!)</h5>";
			}
		}
	}


	public function render() {
		
		//$this->content = include_once(CMSPATH . DS . 'templates' . DS . $template . DS . 'index.php');
		
		//$this->include_once_content (CMSPATH .'/templates/' . $template . '/index.php');
		// if ADMIN but guest, show login
		if (ADMINPATH && $this->user->username=="guest") {
			// check for login attempt
			$username = $this->getvar('username','USERNAME');
			$password = $this->getvar('password','RAW');
			$login_user = new User();
			/* CMS::pprint_r ($username); CMS::pprint_r ($password);
			exit(0); */
			if ($username && $password) {
				if ($login_user->load_from_username($username)) {
					
					// user exists, check password
					if ($login_user->check_password($password)) {
						// logged in!
						s::set('user_id',$login_user->id);
						$this->queue_message('Welcome ' . $login_user->username, 'success',Config::$uripath . "/admin");
						//echo "<p>welcome {$login_user->username}</p>";
					}
					else {
						$this->queue_message('Incorrect username or Password','danger',Config::$uripath . "/admin");
						//echo "<p>Incorrect password</p>";
					}
				}
				else {
					$this->queue_message('Incorrect username or password','danger',Config::$uripath . "/admin");
					//echo "<p>Incorrect username</p>";
				}
			}
			$template="clean";
			include_once (CURPATH . '/templates/' . $template . "/login.php");
			//$this->pprint_r ($login_user);
			//$this->showinfo();
		}
		else {
			$template = "basic";
			if (ADMINPATH) {
				$template = "clean";
			}
			include_once (CURPATH . '/templates/' . $template . "/index.php");
			// perform content filtering / plugins
		}
	}
}

// CLASS AUTOLOADER

spl_autoload_register(function($class_name) 
{
	if ($class_name=="CMS") {
		echo "<h1>wtf - cms class should not be required before its loaded itself below!</h1>";
		exit (0);
		//return false;
	}

	// get path to class file
	$is_field_class = strpos($class_name, "Field_");
	if ($is_field_class === false) {
		// not field, assume regular core class
		$path = CMSPATH . "/core/" . strtolower($class_name) . ".php";
	}
	else {
		$path = CMSPATH . "/core/fields/" . $class_name . ".php";
	}
	

	
	//echo "<h1>autoload path: " . $path . "</h1>";
    require_once $path;
});

CMS::Instance()->render();

