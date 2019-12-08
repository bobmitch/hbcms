<?php
defined('CMSPATH') or die; // prevent unauthorized access

// router

$segments = CMS::Instance()->uri_segments;
if (sizeof($segments)==1) {
	$view = 'default';
}
else {
	$view = $segments[1];
}

// load model + view

$content_type_controller = new Controller(realpath(dirname(__FILE__)),$view);
$content_type_controller->load_view($view);


