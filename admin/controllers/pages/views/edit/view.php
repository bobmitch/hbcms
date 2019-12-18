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
	margin:0rem;
	padding:0rem;
	border:1px dashed rgba(0,0,0,0.0);
	max-height:0;
	overflow: hidden;
	transition:all 1s ease;
	opacity:0;
	display: flex;
    /* justify-content: space-around; */
}
#content_type_controller_views {
	padding-right:1rem;
	margin-right:1rem;
	border-right:1px dashed rgba(0,0,0,0.2);
}
#content_type_wrap.active {
	margin:1rem;
	padding:1rem;
	border:1px dashed rgba(0,0,0,0.2);
	max-height:100vh;
	opacity:1;
}

.fields-horizontal {
	display:flex;
}
.fields-horizontal > * {
	padding-left:1em;
	flex-grow: 1;
  flex-shrink: 1;
  flex-basis: 0;
}
.fields-horizontal > *:first-child {
	padding-left:0;
}
.lighter_note {
	font-size:80%;
	opacity:0.6;
	padding-left:1rem;
}

/* #content_type_controller_views {
	margin:0rem;
	padding:0rem;
	border:1px dashed rgba(0,0,0,0.0);
	max-height:0;
	overflow: hidden;
	transition:all 1s ease;
	opacity:0;
}
#content_type_controller_views.active {
	margin:1rem;
	padding:1rem;
	border:1px dashed rgba(0,0,0,0.2);
	max-height:100vh;
	opacity:1;
} */

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
<form method="POST" onSubmit="return validate_view_options();" action="<?php echo Config::$uripath . "/admin/pages/save";?>" id="page_form">
		<input name="id" type="hidden" value="<?php echo $page->id;?>"/>
<div class='fields-horizontal'>
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

	<div class="field ">
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
					<i class="fas fa-project-diagram"></i>
				</span>
			</div>
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	</div>

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
	</div>

</div> <!-- end div fields-horizontal -->

	<hr>

<div id='content_type_section' class='fields-horizontal'>
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

	<div id="content_type_wrap" class="<?php echo $page->content_type . " "; if ($page->content_type>0) {echo " active ";}?>">
		
		<div id="content_type_controller_views">
			<h6 class='heading title is-6'>CHOOSE VIEW</h6>
			<div class='control'>
				<div class='select'>
					<select  id='content_type_controller_view' name='content_type_controller_view'>
						<option value=''>Choose View:</option>
						<?php
						$all_views = CMS::Instance()->pdo->query('select * from content_views where content_type_id=' . $page->content_type)->fetchAll();
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
			<h6 class='heading title is-6'>VIEW OPTIONS</h6>
			<?php 
				if ($page->content_type>0) {
					$options_array = json_decode($page->view_configuration);
					$content_loc = Content::get_content_location($page->content_type);
					$view_loc = Content::get_view_location($page->view);
					include_once (CMSPATH . "/controllers/" . $content_loc . "/views/" . $view_loc . "/options.php");
				}
			?>
		</div>

	</div>
</div> <!-- end content_type_wrap -->
	<hr>

	


	<label class="label" for="template_layout_container">Widget Assignments</label>
	<div id="template_layout_container">
		<?php include_once($layout_path); ?>
	</div>

	<div class="control">
		<button type="submit" class="button is-primary">Save</button>
	</div>
	
</form>

<script>

	function validate_view_options() {
		view_options = document.getElementById('content_type_controller_view_options');
		return true;
	}


	content_type = document.getElementById("content_type");
	window.content_type_id = content_type.value;
	window.loaded_content_type_id = content_type.value;
	content_type_wrap = document.getElementById("content_type_wrap");
	content_type_controller_views = document.getElementById('content_type_controller_views');
	

	// switch views based on content type
	content_type.addEventListener('change',function(e){
		content_type_controller_views = document.getElementById('content_type_controller_views');
		window.content_type_id = e.target.value;
		if (window.content_type_id == -1) {
			//content_type_wrap.style.display = 'none';
			content_type_wrap.classList.remove('active');
			document.getElementById('content_type_controller_view').required=false; // prevent invisible required element from submitting form
			// todo: make sure no views are selected
		}
		else {
			// check to see if content type chosen differs from already loaded type
			// if it doesn't match, need to reload based on new content type so views are available
			if (window.content_type_id = window.loaded_content_type_id) {
				content_type_wrap.classList.add('active');
				document.getElementById('content_type_controller_view').required=true;
				window.controller_location = e.target.querySelector('option:checked').dataset.controller_location;
			}
			else {
				window.location.href = "<?php echo Config::$uripath . "/admin/pages/edit/" . $page->id . "/";?>" + window.content_type_id + '#ccccontent_type_controller_views';
			}
		}
	});


	// TODO - fix multiselects for localstorage

	function unserialize_form(id) {
		var form_json = window.localStorage.getItem(id);
		if (!form_json) {
			console.log('No saved details from change of content_type / view');
			return false;
		}
		var form = document.getElementById(id);
		if (!form) {
			return false;
		} 
		form_data = JSON.parse(form_json);
		form_data.forEach(form_item => {
			console.log('Looking for form element with name: ', form_item.field_name);
			matching_form_element = document.querySelector('[name="' + form_item.field_name + '"]');
			if (matching_form_element) {
				console.log('Inserting stored item: ', form_item);
				matching_form_element.value = form_item.field_value;
			}
			else {
				console.log('Error deserializing form. No element with name matching: ',form_item.field_name);
			}
		});
		window.localStorage.removeItem(id);
	}

	function serialize_form(id) {
		var form = document.getElementById(id);
		if (!form) {
			return false;
		}
		// Setup our serialized data
		var serialized = [];
		// Loop through each field in the form
		for (var i = 0; i < form.elements.length; i++) {
			var field = form.elements[i];
			// Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
			if (!field.name || field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') continue;
			// If a multi-select, get all selections
			if (field.type === 'select-multiple') {
				for (var n = 0; n < field.options.length; n++) {
					if (!field.options[n].selected) continue;
					serialized.push({"field_name":field.name,"field_value":field.options[n].value});
				}
			}
			// Convert field data to a query string
			else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
				serialized.push({"field_name":field.name,"field_value":field.value});
			}
		}
		serialized_json = JSON.stringify(serialized);
		window.localStorage.setItem(id,serialized_json);
	}

	// TODO: check for localstorage form values and populate accordingly
	unserialize_form('page_form');
	
	if (content_type_controller_view.value>0) {
		content_type_wrap.classList.add('active');
		document.getElementById('content_type_controller_view').required=true;
		window.controller_location = content_type.querySelector('option:checked').dataset.controller_location;
			
	}

	// switch view options based on view
	content_type_controller_views.addEventListener('change',function(e){
		// TODO: save to session somehow preventing loss of data on url change
		serialize_form('page_form'); // save into local storage

		// reload page with new view options
		view_location = document.getElementById('content_type_controller_view').querySelector('option:checked').dataset.view_location;
		window.content_view_id = e.target.value;
		window.location.href = "<?php echo Config::$uripath . "/admin/pages/edit/" . $page->id . "/";?>" + window.content_view_id + '#ccccontent_type_controller_views';
	
	});

	
</script>