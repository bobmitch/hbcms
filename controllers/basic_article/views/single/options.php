<?php

	define('CMSPATH',true);
	include('./../../../../config.php');
	include('./../../../../core/db.php');
	$db = new db();
	$all_articles = $db->pdo->query("select * from content where content_type=1 and state=1 order by id ASC")->fetchAll();
	?>

	<div class="field">
		<div class='control'>
			<div class='select'>

				<select name="view_options[]">
					<option value='' required>Select Article:</option>
					<?php foreach ($all_articles as $article):?>
						<option value='<?php echo $article->id;?>'><?php echo $article->title;?></option>
					<?php endforeach; ?>
				</select>

			</div>
		</div>
	</div>

