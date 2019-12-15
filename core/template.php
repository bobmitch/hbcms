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
		$this->positions = json_decode(file_get_contents(CMSPATH . '/templates/' . $this->folder . '/positions.json'))->positions;
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

	static public function get_default_template() {
		//echo "<p>Getting all users...</p>";
		$result = CMS::Instance()->pdo->query("select * from templates where is_default=1")->fetch();
		return $result;
	}

	public function output_widget_admin($position, $page_id) {
		// takes a position name (ostensibly given in a layout.php file within a template)
		// and lists/details widgets that are valid for current page/layout combination
		// if $this->page_id is null - probably creating a new page, so return any widgets 
		// that also have ALL pages and default position matching this one.
		// if page_id is set, and widget override in place, check page_id + position combo in override 
		// using ordering field in main widget table do determine position.
		
		$query = 'select id,title,state from widgets where ((position_control=1 and not find_in_set(?, page_list)) OR (position_control=0 and find_in_set(?, page_list))) and global_position=? and state>=0';
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($page_id, $page_id, $position));
		$all_global_widgets = $stmt->fetchAll();
		//CMS::pprint_r ($all_global_widgets);

		echo "<div class='template_layout_widget_wrap'>";
			echo "<h2>{$position} <span class='widget_count'>(" . sizeof($all_global_widgets) . ")</span></h2>";
			echo "<div class='tags'>";
			foreach ($all_global_widgets as $widget) {
				$state_class='is-info';
				$publish_note = "";
				if ($widget->state==0) {
					$state_class='is-warning is-light';
					$publish_note="<span class='lighter_note'> (unpublished)</span></a>";
				}
				echo "<a href='".Config::$uripath."/admin/widgets/edit/".$widget->id."'><span class='tag {$state_class}'>" . $widget->title .  $publish_note . "</span></a>"  ;
			}
			echo "</div>";
		echo "</div>";
	}

	

	



}