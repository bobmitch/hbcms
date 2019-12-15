
<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Widget {
	public $id;
	public $title;
	public $type_id;
	public $type;
	public $state;
	public $options;

	public static function get_all_widget_types() {
		return CMS::Instance()->pdo->query('select * from widget_types')->fetchAll();
	}

	public function get_type_object() {
		$this->type = CMS::Instance()->pdo->query('select * from widget_types where id=' . $this->type_id)->fetch();
	}

	public function get_option($option_name) {
		foreach ($this->options as $option) {
			if (property_exists($option, $option_name)) {
				return $option->$option_name;
			}
		}
		return false;
	}

	public function show_admin_form() {
		$this->form = new Form();
		$this->form->load_json(CMSPATH . "/widgets/");
	}

	public function load($id) {
		$info = CMS::Instance()->pdo->query('select * from widgets where id=' . $id)->fetch();
		$this->id = $info->id;
		$this->title = $info->title;
		$this->type_id = $info->type;
		$this->state = $info->state;
		$this->note = $info->note;
		$this->options = json_decode($info->options);
		$this->position_control = $info->position_control;
		$this->global_position = $info->global_position;
		$this->page_list = explode(',', $info->page_list);
	}

	public static function get_widget_type_title($widget_type_id) {
		if (is_numeric($widget_type_id)) {
			return CMS::Instance()->pdo->query('select title from widget_types where id=' . $widget_type_id)->fetch()->title;
		}
		else {
			return false;
		}
	}

	public function save($required_details_form, $widget_options_form, $position_options_form) {
		// update this object with submitted and validated form info
		$this->title = $required_details_form->get_field_by_name('title')->default;
		$this->state = $required_details_form->get_field_by_name('state')->default;
		$this->note = $required_details_form->get_field_by_name('note')->default;
		$this->options = array();
		foreach ($widget_options_form->fields as $option) {
			$obj = new stdClass();
			$obj->name = $option->name;
			$obj->value = $option->default;
			//$obj->{$option->name} = $option->default;
			$this->options[] = $obj;
		}
		// get position options fields
		$this->position_control = $position_options_form->get_field_by_name('position_control')->default;
		$this->global_position = $position_options_form->get_field_by_name('global_position')->default;
		$this->page_list = $position_options_form->get_field_by_name('position_pages')->default;

		$options_json = json_encode($this->options);

		if ($this->id) {
			// update
			$query = "update widgets set state=?, title=?, note=?, options=?, position_control=?, global_position=?, page_list=? where id=?";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$params = array($this->state, $this->title, $this->note, $options_json, $this->position_control, $this->global_position, implode(',',$this->page_list), $this->id) ;
			$result = $stmt->execute( $params );
			
			if ($result) {
				CMS::Instance()->queue_message('Widget updated','success',Config::$uripath . '/admin/widgets/show');	
			}
			else {
				CMS::Instance()->queue_message('Widget failed to save','danger',Config::$uripath . $_SERVER['REQUEST_URI']);	
			}
		}
		else {
			// new
			$query = "insert into widgets (state,type,title,note,options,position_control,global_position,page_list) values(?,?,?,?,?,?,?,?)";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$params = array($this->state, $this->type_id, $this->title, $this->note, $options_json, $this->position_control, $this->global_position, implode(',',$this->page_list)) ;
			$result = $stmt->execute( $params );
			if ($result) {
				CMS::Instance()->queue_message('New widget saved','success',Config::$uripath . '/admin/widgets/show');	
			}
			else {
				CMS::Instance()->queue_message('New widget failed to save','danger',Config::$uripath . $_SERVER['REQUEST_URI']);	
			}
		}
	}
}