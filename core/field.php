<?php
defined('CMSPATH') or die; // prevent unauthorized access

// BASE CLASS FOR FIELDS
class Field {
	public $id;
	public $title;
	public $name; // unique id for form submit

	public function display() {
		echo "<label class='label'>Field Label</label>";
		echo "<p>Hello, I am a field!</p>";
	}

	public function designer_display() {
		echo "<label class='label'>Field Label</label>";
		echo "<p>Hello, I am a field!</p>";
	}
}