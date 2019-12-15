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

.state1 {
	color:rgba(50,200,50,0.5);
}


</style>



<h1 class='title is-1'>
	All Pages
	<a href='<?php echo Config::$uripath . "/admin/pages/edit/0"?>' class="button is-primary pull-right">
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
				<?php 
				// TODO: implement proper publish/unpublish switching
				// ajax vs standalone form? form easier...
				//echo $page->state; 
				?>
				<i class="state1 fas fa-check-square"></i>
				
			</td>
			<td>
				<?php
				for ($n=0; $n<$page->depth; $n++) {
					echo "<span class='child_indicator'>-&nbsp;</span>";
				}
				?>
				<a href='<?php echo Config::$uripath . "/admin/pages/edit/" . $page->id . "/" . $page->content_view;?>'><?php echo $page->title; ?></a>
				<br>
				<?php 
				if ($page->content_type > 0) {
					echo "<span class='unimportant'>" . Content::get_content_type_title($page->content_type) ;
					echo " &raquo; ";
					echo Content::get_view_title($page->content_view) . "</span>";
					//echo "<br><p>TODO: get options nice</p>";
					$component_path = Content::get_content_location($page->content_type);
					$component_view = Content::get_view_location($page->content_view);
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