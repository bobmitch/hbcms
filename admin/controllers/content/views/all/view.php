<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>

<style>
table.table {
	width:100%;
}
.position_single_wrap {
	font-size:70%;
	padding-top:0.3rem;
	border-top:1px solid rgba(0,0,0,0.1);
	margin-top:0.3rem;
	opacity:0.6;
}
span.position_single {
	font-weight:bold;
}
span.page_list, td.note_td, .lighter_note, .usage {
	font-size:70%; opacity:0.6;
}

div.pull-right {
	/* display:inline-block; */
}
#content_operations {
	margin-right:2rem;
}
.state1 {
	color:#00d1b2;
}
.state0 {
	color:#f66;
}
.hidden_multi_edit {
	display:none;
	/* display:inline-block; */
}
.content_admin_row.selected {
	background:rgba(200,255,200,0.3);
}


</style>

<form action='' method='post' name='content_action' id='content_action_form'>

<h1 class='title is-1'>All Content
<?php if ($widget_type_id):?>
	<a class='is-primary pull-right button btn' href='<?php echo Config::$uripath;?>/admin/content/edit/new/<?php echo $content_type_id;?>'>New &ldquo;<?php echo $content_type_title;?>&rdquo; Content</a>
	<?php else: ?>
		<div class='field pull-right'>
			<label class='label'>New Content</label>
			<div class='control'>
				<div class='select'>
					<select onchange="choose_new_content_type();" data-widget_type_id='0' id='new_content_type_selector'>
						<option value='666'>Make selection:</option>
						<?php foreach ($all_wcontent_types as $content_type):?>
						<option value='<?php echo $content_type->id;?>'><?php echo $content_type->title;?></option>
						<?php endforeach; ?>
					</select>
					<script>
					function choose_new_content_type() {
						new_id = document.getElementById("new_content_type_selector").value;
						window.location.href = "<?php echo Config::$uripath;?>/admin/content/edit/new/" + new_id;
					}
					</script>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<!-- content operation toolbar -->
	<div id="content_operations" class="pull-right buttons has-addons">
		<button formaction='<?php echo Config::$uripath;?>/admin/content/action/publish' class='button is-primary' type='submit'>Publish</button>
		<button formaction='<?php echo Config::$uripath;?>/admin/content/action/unpublish' class='button is-warning' type='submit'>Unpublish</button>
		<button formaction='<?php echo Config::$uripath;?>/admin/content/action/delete' onclick='return window.confirm("Are you sure?")' class='button is-danger' type='submit'>Delete</button>
	</div>
</h1>

<?php if (!$all_content):?>
	<h2>No content to show!</h2>
<?php else:?>
	<table class='table'>
		<thead>
			<tr>
				<th>State</th><th>Title</th><th>Type</th><th>Start</th><th>End</th><th>Created By</th><th>Updated By</th><th>Note</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($all_content as $content_item):?>
			<tr class='content_admin_row'>
				<td>
					<input class='hidden_multi_edit' type='checkbox' name='id[]' value='<?php echo $content_item->id; ?>'/>
					<button class='button' type='submit' formaction='<?php echo Config::$uripath;?>/admin/content/action/toggle' name='id[]' value='<?php echo $content_item->id; ?>'>
						<?php 
						if ($content_item->state==1) { 
							echo '<i class="state1 is-success fas fa-check-circle" aria-hidden="true"></i>';
						}
						else {
							echo '<i class="state0 fas fa-times-circle" aria-hidden="true"></i>';
						} ?>
					</button>
				</td>
				<td><a href="<?php echo Config::$uripath; ?>/admin/content/edit/<?php echo $content_item->id;?>"><?php echo $content_item->title; ?></a></td>
				<td><?php echo Content::get_content_type_title($content_item->content_type); ?></td>
				<td><?php echo $content_item->start; ?></td>
				<td><?php echo $content_item->end; ?></td>
				<td><?php echo User::get_username_by_id($content_item->created_by); ?></td>
				<td><?php echo User::get_username_by_id($content_item->updated_by); ?></td>
				<td><?php echo $content_item->note; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

</form>

<script>
	admin_rows = document.querySelectorAll('.content_admin_row');
	admin_rows.forEach(row => {
		row.addEventListener('click',function(e){
			tr = e.target.closest('tr');
			tr.classList.toggle('selected');
			hidden_checkbox = tr.querySelector('.hidden_multi_edit');
			hidden_checkbox.checked = !hidden_checkbox.checked;
		});
	});
</script>