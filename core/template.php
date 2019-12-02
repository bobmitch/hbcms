<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Template {
	public $id;
	public $title;
	public $folder;
	public $description;
	public $layout;
	public $page_id;
	public $positions; // array of tuples - position alias, position title

	public function __construct($id=1) {
		$stmt = CMS::Instance()->pdo->prepare("select * from templates where id=?");
		$stmt->execute(array($id));
		$template = $stmt->fetch();
		$this->id = $template->id;
		$this->title = $template->title;
		$this->folder = $template->folder;
		$this->description = $template->description;
		//$this->layout = null;
		$this->positions = null;
		$this->page_id = null; // nominally used in back-end to inform template of page being edited for layout/position purposes
	}

	public function get_position_title($position_alias) {
		for ($n=0; $n<sizeof($this->positions); $n++) {
			if ($this->positions[$n][0]==$position_alias) {
				return $this->positions[$n][1];
			}
		}
		return "";
	}
	
	// $pdo->prepare($sql)->execute([$name, $id]);
	static public function get_all_templates() {
		//echo "<p>Getting all users...</p>";
		$result = CMS::Instance()->pdo->query("select * from templates")->fetchAll();
		return $result;
	}

	public function output_widget_admin($position) {
		// takes a position name (ostensibly given in a layout.php file within a template)
		// and lists/details widgets that are valid for current page/layout combination
		// if $this->page_id is null - probably creating a new page, so return any widgets 
		// that also have ALL pages and default position matching this one.
		// if page_id is set, and widget override in place, check page_id + position combo in override 
		// using ordering field in main widget table do determine position.
		echo "<h2>{$position}</h2>";
	}

	

	



}