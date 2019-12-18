<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Form {
	public $location; // relative to CMS path for json config
	public $fields;

	function __construct($path=CMSPATH . "/testform.json") {
		$this->fields = array();
		$this->location = "";
		$this->load_json($path);
	}

	public function load_json($path = CMSPATH . "/testform.json") {
		$json = file_get_contents($path);
		
		$obj = json_decode($json);
		if (!$obj) {
			CMS::Instance()->queue_message('Invalid JSON found in: ' . $path,'danger',Config::$uripath.'/admin');
		}
		$tempfields = $obj->fields;
		$this->id = $obj->id;
		//CMS::pprint_r ($tempfields);
		foreach ($tempfields as $field_config) {
			$class = "Field_" . $field_config->type;
			$thisfield = new $class();
			$thisfield->load_from_config($field_config);
			$this->fields[] = $thisfield;
		}
	}

	public function set_from_submit() {
		foreach ($this->fields as $field) {
			$field->set_from_submit();
		}
	}

	public function get_field_by_name($field_name) {
		foreach ($this->fields as $field) {
			if ($field->name == $field_name) {
				return $field;
			}
		}
		CMS::pprint_r ($this);
		exit(0);
		CMS::Instance()->queue_message("Field &ldquo;{$field_name}&rdquo; not found. Check form JSON file.",'danger',Config::$uripath."/admin");
		return false;
	}

	public function is_submitted() {
		if ($this->id) {
			$form_name = CMS::getvar("form_" . $this->id, "TEXT");
			if ($form_name) {
				return true;
			}
		}
		return false;
	}

	public function validate() {
		foreach ($this->fields as $field) {
			if (!$field->validate()) {
				return false;
			}
		}
		return true;
	}

	public function display_front_end() {
		// loop through fields and call display();
		//CMS::pprint_r ($this);
		
		foreach ($this->fields as $field) {
			echo "<div class='hbcms_form_field'>";
			$field->display();
			echo "</div>";
		}
		echo "<input type='hidden' value='1' name='form_" . $this->id . "'>";
	}
}