<?php
defined('CMSPATH') or die; // prevent unauthorized access

// any variables created here will be available to the view

$page = new Page();

$success=$page->load_from_post();

if (!$success) {
	CMS::Instance()->queue_message('Failed to create page object from form data','danger',Config::$uripath.'/admin/pages');
}

/* CMS::pprint_r ($page);
CMS::pprint_r ($page->view_configuration);
exit(0); */

$success = $page->save();

if ($success) {
	CMS::Instance()->queue_message('Page created/updated','success',Config::$uripath.'/admin/pages');
}
else {
	CMS::Instance()->queue_message('Page creation/update failed','danger',Config::$uripath.'/admin/pages');
}
