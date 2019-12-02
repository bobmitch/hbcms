<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Controller {
	public $path;
	public $view;

	public function __construct($path, $view) {
		$this->path = $path;
		$this->view = $view;
	}

	public function load_view ($view) {
		// first check folder exists
		// then load model (this then loads the view)
		$view_path = $this->path . "/views/" . $this->view;
		if (file_exists ($view_path)) {
			if (is_dir($view_path)) {
				// TODO: check for included files existin too
				include_once ($view_path . "/model.php");
				include_once ($view_path . "/view.php");
			}
		}
		else {
			CMS::Instance()->show_error ("Failed to load view {$this->view} for controller at " . $this->path, 'error');
		}
	}
}