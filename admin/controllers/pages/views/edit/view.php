<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>

<style>
#template_layout_container {
	margin:2rem auto;
	padding:1rem;
	border:2px solid rgba(0,0,0,0.2);
}
#content_type_wrap {
	margin:1rem;
	padding:1rem;
	border:1px dashed rgba(0,0,0,0.2);
}
</style>

<?php
//CMS::pprint_r ($page);
?>

<h1 class='title is-1'>
	<?php if ($page->id):?>
	Edit Page &ldquo;<?php echo $page->title;?>&rdquo;
	<?php else:?>
	New Page 
	<?php endif; ?>
</h1>
<form method="POST" onSubmit="return validate_view_options();" action="<?php echo Config::$uripath . "/admin/pages/save";?>" id="new_user_form">
		<input name="id" type="hidden" value="<?php echo $page->id;?>"/>
	<div class="field">
		<label class="label">Title</label>
		<div class="control has-icons-left has-icons-right">
			<input required name="title" class="input iss-success" type="text" placeholder="Page Title" value="<?php echo $page->title;?>">
			<span class="icon is-small is-left">
			<i class="fas fa-heading"></i>
			</span>
			<!-- <span class="icon is-small is-right">
				<i class="fas fa-check"></i>
			</span> -->
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	</div>

	<div class="field">
		<label class="label">URL Segment</label>
		<div class="control has-icons-left has-icons-right">
			<input name="alias" class="input iss-success" type="text" placeholder="URL Segment" value="<?php echo $page->alias;?>">
			<span class="icon is-small is-left">
			<i class="fas fa-signature"></i>
			</span>
			<!-- <span class="icon is-small is-right">
				<i class="fas fa-check"></i>
			</span> -->
		</div>
	<p class="help">Used as the pages identifier in the URL. i.e. If the parent is 'blog' and you choose 'food' as the segment name, the URL will <strong>be https://example.com/blog/<em>food</em></strong>.
	<br>If blank, it will be based on the title. Alphanumeric characters only, no spaces.</p> 
	</div>

	<div class="field">
		<label class="label">Parent</label>
		<div class="control has-icons-left has-icons-right">
			<div class="select">
				<select id="parent" name="parent">
					<option value="-1">None</option>
					<?php $all_pages = Page::get_all_pages();?>
					<?php foreach ($all_pages as $a_page):?>
						<?php
							// skip if self!
							if ($a_page->id==$page->id) {continue;}
						?>
						<option 
							<?php if ($page->parent == $a_page->id) { echo " selected ";}?>
							value="<?php echo $a_page->id;?>" >
								<?php echo $a_page->title;?>
						</option>
					<?php endforeach; ?>
				</select>
				<span class="icon is-small is-left">
					<i class="fas fa-object-group"></i>
				</span>
			</div>
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	</div>

	<hr>

	<div class="field">
		<label class="label">Main Content</label>
		<div class="control has-icons-left has-icons-right">
		 
		 	<div class="select">
				<select id="content_type" name="content_type">
					<option value="-1">None</option>
					<?php foreach ($all_content_types as $a_content_type):?>
						
						<option 
							<?php if ($page->content_type == $a_content_type->id) { echo " selected ";}?>
							data-controller_location="<?php echo $a_content_type->controller_location;?>" 
							value="<?php echo $a_content_type->id;?>" >
								<?php echo $a_content_type->title;?>
						</option>
					<?php endforeach; ?>
				</select>
				<span class="icon is-small is-left">
					<i class="fas fa-object-group"></i>
				</span>
				<!-- <span class="icon is-small is-right">
					<i class="fas fa-check"></i>
				</span> -->
			</div>
		</div>
	<p class="help">Choose main content and presentation options. Leaving this blank is fine, but all visible content on the site will just be widgets!</p> 
	</div>

	<div id="content_type_wrap" <?php if (!$page->content_type) { echo " style='display:none;' ";}?> >
		<h6 class='heading title is-6'>Display Options</h6>
		<div id="content_type_controller_views">
			<div class='control'>
				<div class='select'>
					<select  id='content_type_controller_view' name='content_type_controller_view'>
						<option value=''>Choose View:</option>
						<?php
						$all_views = CMS::Instance()->pdo->query('select * from content_views')->fetchAll();
						foreach ($all_views as $view) {
							$view_selected = "";
							if ($page->view==$view->id) {
								$view_selected = ' selected ';
							}
							echo "<option {$view_selected} value='".$view->id."' data-view_location=" . $view->location . " data-content_type_id=" . $view->content_type_id . ">" . $view->title . "</option>";
						}
						?>
					</select>
				</div>
			</div>
		</div>

		<div id="content_type_controller_view_options">
		</div>

	</div>

	<hr>

	<div class="field">
		<label class="label">Template</label>
		<div class="control has-icons-left has-icons-right">
			<div class="select">
				<select name="template">
					<?php foreach ($all_templates as $a_template):?>
						<option <?php if ($a_template->id = $default_template) {echo "selected";}?> value="<?php echo $a_template->id;?>" ><?php echo $a_template->title;?></option>
					<?php endforeach; ?>
				</select>
				<span class="icon is-small is-left">
					<i class="fas fa-object-group"></i>
				</span>
				<!-- <span class="icon is-small is-right">
					<i class="fas fa-check"></i>
				</span> -->
			</div>
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	
	</div>
	<label class="label" for="template_layout_container">Widget Assignments</label>
	<div id="template_layout_container">
		<?php include_once($layout_path); ?>
	</div>

	<div class="control">
		<button type="submit" class="button is-primary">Save</button>
	</div>
	
</form>

<script>

	function ajax_get(url, callback) {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				console.log('responseText:' + xmlhttp.responseText);
				try {
					var data = JSON.parse(xmlhttp.responseText);
				} catch(err) {
					console.log(err.message + " in " + xmlhttp.responseText);
					return;
				}
				callback(data);
			}
		};
	
		xmlhttp.open("GET", url, true);
		xmlhttp.send();
	}

	function ajax_get_html(url, callback) {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				callback(xmlhttp.responseText);
			}
		};
	
		xmlhttp.open("GET", url, true);
		xmlhttp.send();
	}

	function validate_view_options() {
		view_options = document.getElementById('content_type_controller_view_options');
		return true;
	}

	

/* 	function show_views(d) {
		views_markup = '<h3>Choose View</h3><div class="select"><select required id="controller_view" name="controller_view">';
		views_markup += "<option value=''>Please select</option>";
		content_type_controller_views = document.getElementById('content_type_controller_views');
		d.views.forEach(view => {
			views_markup += '<option value="' + view.location + '">' + view.title + '</option>';
		});
		views_markup += "</div>";
		content_type_controller_views.innerHTML = views_markup;
	} */

	function show_view_options(d) {
		content_type_controller_view.innerHTML = d;
	}

	content_type = document.getElementById("content_type");
	// check if content type selected on page load to get type id
	window.content_type_id = content_type.value;
	content_type_wrap = document.getElementById("content_type_wrap");
	content_type_controller_views = document.getElementById('content_type_controller_views');

	// switch views based on content type
	content_type.addEventListener('change',function(e){
		content_type_controller_views = document.getElementById('content_type_controller_views');
		window.content_type_id = e.target.value;
		if (window.content_type_id == -1) {
			content_type_wrap.style.display = 'none';
			document.getElementById('content_type_controller_view').required=false;
			//content_type_controller_views.style.display = 'none';
			//content_type_controller_views.innerHTML = ''; // ensure main content form options are blank
			// todo: make sure no views are selected
		}
		else {
			//content_type_controller_views.style.display = 'block';
			content_type_wrap.style.display = 'block';
			document.getElementById('content_type_controller_view').required=true;
			window.controller_location = e.target.querySelector('option:checked').dataset.controller_location;
			//window.controller_location = e.target.value;
			ajax_get('<?php echo Config::$uripath;?>/controllers/' + window.controller_location + '/views.json',function(d){
				console.log(d);
				//show_views(d); // deprecated - sourcing all from db now
				// filter options according to window.content_type_id matching data content_type_id on option
			}); 
		}
	});

	function update_view_options() {
		// triggered by onchange event below + on page edit with content options
		view_location = document.getElementById('content_type_controller_view').querySelector('option:checked').dataset.view_location;
		markup = ajax_get_html('<?php echo Config::$uripath;?>/controllers/' + window.controller_location + '/views/' + view_location + '/options.php?view=render_admin',function(html){
			content_type_controller_view_options = document.getElementById('content_type_controller_view_options');
			content_type_controller_view_options.innerHTML = html;
		})
	}

	// switch view options based on view
	content_type_controller_views.addEventListener('change',function(e){
		/* view_location = e.target.querySelector('option:checked').dataset.view_location;
		
		markup = ajax_get_html('<?php echo Config::$uripath;?>/controllers/' + window.controller_location + '/views/' + view_location + '/options.php?view=render_admin',function(html){
			content_type_controller_view_options = document.getElementById('content_type_controller_view_options');
			content_type_controller_view_options.innerHTML = html;
		}) */
		//alert(e.target.value);
		update_view_options();
	});

	<?php
	if ($page->view > 0) {
		
		// need to load page options now and now just when view select changes
		?>
		window.controller_location = content_type.querySelector('option:checked').dataset.controller_location;
		update_view_options();
		<?php
	}
	?>
</script>