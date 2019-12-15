<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>

<?php if ($new_widget):?>
	<h1 class='title'>New &ldquo;<?php echo $widget->type->title;?>&rdquo; Widget</h1>
	<p class='help'><?php echo $widget->type->description;?></p>
<?php else:?>
	<h1 class='title'>Editing &ldquo;<?php echo $widget->title; ?>&rdquo; Widget</h1>
	<p class='help'><?php echo $widget->type->description;?></p>
<?php endif; ?>

<hr>

<form method="POST" action="">

<div class='flex'>
	<?php $required_details_form->display_front_end(); ?>
</div>

<hr>
<h5 class='title'>Main Widget Setup</h5>

<?php 
$widget_options_form->display_front_end();

?>
<style>
div.flex {display:flex;}
div.flex > * {padding-left:2rem;}
div.flex > div:first-child {padding-left:0;}
</style>

<hr>
<h6 class='title'>Position Options</h6>
<p class='help'>Choose a default template position and pages where this widget will appear.</p>
<br>
<div class='flex'>
	<?php $position_options_form->display_front_end(); ?>
</div>

<hr>
<button class='button is-primary' type='submit'>Save</button>
</form>

