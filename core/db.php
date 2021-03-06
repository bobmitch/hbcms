<?php
//defined('CMSPATH') or die; // prevent unauthorized access

if (defined('CMSPATH')) {
	class db {
		public $pdo;

		public function __construct() {
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
					CMS::show_error("Failed to connect to database: " . Config::$dbname);
				}
			}
		}
	}
}
else {
	include_once('../config.php');
	class db {
		public $pdo;

		public function __construct() {
			$dsn = "mysql:host=" . Config::$dbhost . ";dbname=" . Config::$dbname . ";charset=" . Config::$dbchar;
			$options = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
				PDO::ATTR_EMULATE_PREPARES   => false,
			];
			try {
				$this->pdo = new PDO($dsn, Config::$dbuser, Config::$dbpass, $options);
			} catch (\PDOException $e) {
				return false;
			}
		}
	}
}		
