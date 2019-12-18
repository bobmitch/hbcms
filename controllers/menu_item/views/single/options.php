<?php
	$options_all_menu_items = CMS::Instance()->pdo->query("select * from content where content_type=2 and state=1 order by id ASC")->fetchAll();
	//CMS::pprint_r ($options_array);
?>
	<div class="field">
		<div class='control'>
			<div class='select'>
				<select required name="view_options[]">
					<option value='' >Select Menu Item:</option>
					<?php foreach ($options_all_menu_items as $article):?>
						<option <?php if ($options_array[0]==$article->id) { echo " selected ";}?> value='<?php echo $article->id;?>'><?php echo $article->title;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>

