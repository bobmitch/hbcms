<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>
<h1 class='title is-1'>All Content</h1>

<?php if (!$all_content):?>
	<h2>No content to show!</h2>
<?php else:?>
	<table class='table'>
		<thead>
			<tr>
				<th>State</th><th>Title</th><th>Type</th><th>Start</th><th>End</th><th>Created By</th><th>Updated By</th><th>id<th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($all_content as $content_item):?>
			<tr>
				<td><?php echo $content_item->state; ?></td>
				<td><?php echo $content_item->title; ?></td>
				<td><?php echo Content::get_content_type_title($content_item->content_type); ?></td>
				<td><?php echo $content_item->start; ?></td>
				<td><?php echo $content_item->end; ?></td>
				<td><?php echo User::get_username_by_id($content_item->created_by); ?></td>
				<td><?php echo User::get_username_by_id($content_item->updated_by); ?></td>
				<td><?php echo $content_item->id; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>