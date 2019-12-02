<?php
defined('CMSPATH') or die; // prevent unauthorized access

// any variables created here will be available to the view

$page = new Page();
$all_pages = $page->get_all_pages();

//$template = new Template();
$all_templates = Template::get_all_templates();

// TODO: change to user set default
$default_template = 1;

function get_template_title($page_template_id, $all_templates) {
	foreach ($all_templates as $template) {
		if ($page_template_id == $template->id) {
			return $template->title;
		}
	}
	return "Error - Unknown Template";
}
