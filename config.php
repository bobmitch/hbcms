<?php
	defined('CMSPATH') or die; // prevent unauthorized access
	class Config {
		static $dbname = 'flatgen';
		static $dbuser = 'cms.bobmitch';
		static $dbpass = 'Myxolyd1an';
		static $dbhost = 'localhost';
		static $dbchar = 'utf8mb4';
		static $domain = 'auto'; // can be forced for whatever reason, otherwise leave
		static $uripath = '/newcms'; // if site is in sub-folder from www-root add here
		static $template = 'basic';
		static $sitename = "HB CMS Test";
		static $debug = false;
	}
