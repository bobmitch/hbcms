<?php
defined('CMSPATH') or die; // prevent unauthorized access

// CMS::$edit_page_id set in /admin/controllers/pages/views/edit/model.php
// will be zero if editing new page
$edit_page_id = CMS::Instance()->edit_page_id;

//$template->positions = array('Header','Above Content','After Content','Sidebar','Footer');
$template->positions = json_decode(file_get_contents(__DIR__ . '/positions.json'));

if (Config::$debug) {
	CMS::pprint_r ($template->positions);
}

?>

<style>
	
	#hbcms_layout_wrap section, #hbcms_layout_wrap aside {
		margin:0.5rem;
		padding:0.5rem;
		border:1px dashed rgba(0,0,0,0.2);
	}
	#hbcms_layout_wrap p {
		margin:1rem;
		color:rgba(0,0,0,0.2);
	}
	.template_layout_widget_wrap {
		margin:0.5rem;
		padding:0.5rem;
		border:2px dashed rgba(0,0,0,0.1);
	}
	#hbcms_layout_wrap h2 {
		margin-bottom:0.5rem;
	}
	.widget_count {
		font-size:70%;
		opacity:0.6;
	}
	.tags a {
		margin-right: 1rem;
	}
</style>

<div id='hbcms_layout_wrap'>
	<section id="top nav">
		<?php $template->output_widget_admin('Top Nav', $edit_page_id);?>
	</section>
	<section id="header">
		<?php $template->output_widget_admin('Header', $edit_page_id);?>
	</section>
	<section id="main">
		<div id="content">
			<?php $template->output_widget_admin('Above Content', $edit_page_id);?>
			<p>Loreum Ipsum</p>
			<p>End of Content</p>
			<?php $template->output_widget_admin('After Content', $edit_page_id);?>
		</div>
		<aside id="sidebar">
			<?php $template->output_widget_admin('Sidebar', $edit_page_id);?>
		</aside>
	</section>
	<section id="footer">
		<?php $template->output_widget_admin('Footer', $edit_page_id);?>
	</section>
</div>