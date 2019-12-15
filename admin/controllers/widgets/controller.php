<?php
defined('CMSPATH') or die; // prevent unauthorized access

// router

$segments = CMS::Instance()->uri_segments;
if (sizeof($segments)==0) {
	$view = 'default';
	$widget_id = false;
}
else {
	if ($segments[1]=='show') {
		$view = 'show';
	}
	elseif ($segments[1]=='edit') {
		$view = 'edit';
	}
	elseif ($segments[1]=='action') {
		$view = 'action';
	}
}

// load model + view

//CMS::queue_message('Test','success');

$widgets_controller = new Controller(realpath(dirname(__FILE__)),$view);
$widgets_controller->load_view($view);

