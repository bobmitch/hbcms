<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>

<style>
.unimportant {
	font-size:70%;
	opacity:0.5;
}
.child_indicator {
	opacity:0.2;
	font-size:120%;
}
#all_pages_table {
	width:100%;
}
</style>

<?php
$all_pages = Page::get_all_pages_by_depth(); // defaults to parent=-1 and depth=-1
?>

<h1 class='title is-1'>
	All Pages
	<a href='<?php echo Config::$uripath . "/admin/pages/edit"?>' class="button is-primary pull-right">
		<span class="icon is-small">
      		<i class="fas fa-check"></i>
    	</span>
		<span>New Page</span>
	</a>
</h1>

<table id='all_pages_table' class="table">
	<thead>
		<th>Status</th>
		<th>Title</th>
		<th>URL</th>
		<!-- <th>Content</th> -->
		<th>Template</th>
		<!-- <th>Configuration</th> -->
		<!-- <th>Created</th> -->
		<th>ID</th>
	</thead>
	<tbody>
		<?php foreach($all_pages as $page):?>
		<tr>
			<td>
				<?php echo $page->state; ?>
			</td>
			<td>
				<?php
				for ($n=0; $n<$page->depth; $n++) {
					echo "<span class='child_indicator'>-&nbsp;</span>";
				}
				?>
				<a href='<?php echo Config::$uripath . "/admin/pages/edit/" . $page->id;?>'><?php echo $page->title; ?></a>
				<br>
				<?php 
				if ($page->content_type > 0) {
					echo "<span class='unimportant'>" . Content::get_content_type_title($page->content_type) ;
					echo " &raquo; ";
					echo Content::get_view_title($page->content_view) . "</span>";
					//echo "<br><p>TODO: get options nice</p>";
					$component_path = Content::get_content_location($page->content_type);
					$component_view = Content::get_view_location($page->content_view);
					if (Config::$debug) {
						echo "<p>Debug - Content loc: {$component_path} View loc: {$component_view}</p>";
					}
					// TODO - maybe make this an option to view content info on pages overview? it works!
					/* $view_options = new View_Options($component_path, $component_view, $page->content_view_configuration);
					$content_info = $view_options->get_content_info();
					if ($content_info) {
						echo "<p>{$content_info}</p>";
					} */
				}
				else {
					echo "<span class='unimportant'>Widgets only</span>";
				}
				?>
			</td>
			<td>
				<span class='unimportant'><?php echo $page->alias; ?></span>
			</td>
		
			
			<td>
				<span class='unimportant'><?php echo  get_template_title($page->template, $all_templates); ?></span>
			</td>
			<!-- <td>
				<span class='unimportant'><?php echo $page->content_view_configuration; ?></span>
			</td> -->
			<!-- <td>
				<span class='unimportant'><?php echo $page->updated; ?></span>
			</td> -->
			<td>
				<span class='unimportant'><?php echo $page->id; ?></span>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>