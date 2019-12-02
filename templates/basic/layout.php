<?php
defined('CMSPATH') or die; // prevent unauthorized access

$template->positions = array('Header','Above Content','After Content','Sidebar','Footer');

?>

<style>
	
	#hbcms_layout_wrap section, #hbcms_layout_wrap div {
		margin:0.5rem;
		padding:0.5rem;
		border:1px dashed rgba(0,0,0,0.3);
	}
	#hbcms_layout_wrap p {
		margin:1rem;
		color:rgba(0,0,0,0.2);
	}
	
</style>

<div id='hbcms_layout_wrap'>
	<section id="header">
		<?php $template->output_widget_admin('Header');?>
	</section>
	<section id="main">
		<div id="content">
			<?php $template->output_widget_admin('Above Content');?>
			<p>Loreum Ipsum</p>
			<p>End of Content</p>
			<?php $template->output_widget_admin('After Content');?>
		</div>
		<div id="sidebar">
			<?php $template->output_widget_admin('Sidebar');?>
		</div>
	</section>
	<section id="footer">
		<?php $template->output_widget_admin('Footer');?>
	</section>
</div>