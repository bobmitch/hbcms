<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>
<h1 class='title is-1'>
	All Pages
	<a href='<?php echo Config::$uripath . "/admin/pages/new"?>' class="button is-primary pull-right">
		<span class="icon is-small">
      		<i class="fas fa-check"></i>
    	</span>
		<span>New Page</span>
	</a>
</h1>
<table class="table">
	<thead>
		<th>Status</th>
		<th>Title</th>
		<th>URL Segment</th>
		<th>Controller</th>
		<th>Parent</th>
		<th>Template</th>
		<th>Created</th>
		<th>ID</th>
	</thead>
	<tbody>
		<?php foreach($all_pages as $page):?>
		<tr>
			<td>
				<?php echo $page->state; ?>
			</td>
			<td>
				<?php echo $page->title; ?>
			</td>
			<td>
				<?php echo $page->alias; ?>
			</td>
			<td>
				<?php echo $page->controller; ?>
			</td>
			<td>
				<?php echo $page->parent; ?>
			</td>
			<td>
				<?php echo get_template_title($page->template, $all_templates); ?>
			</td>
			<td>
				<?php echo $page->updated; ?>
			</td>
			<td>
				<?php echo $page->id; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>