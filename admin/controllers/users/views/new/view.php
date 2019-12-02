<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>
<h1 class='title is-1'>
	New User
</h1>
<form method="POST" action="<?php echo Config::$uripath . "/admin/users/save";?>" id="new_user_form">
	<div class="field">
		<label class="label">Username</label>
		<div class="control has-icons-left has-icons-right">
			<input required name="username" class="input iss-success" type="text" placeholder="Username" value="">
			<span class="icon is-small is-left">
			<i class="fas fa-user"></i>
			</span>
			<!-- <span class="icon is-small is-right">
				<i class="fas fa-check"></i>
			</span> -->
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	</div>

	<div class="field">
		<label class="label">Email</label>
		<div class="control has-icons-left has-icons-right">
			<input required name="email" class="input iss-success" type="email" placeholder="email@wherever.com" value="">
			<span class="icon is-small is-left">
			<i class="fas fa-envelope"></i>
			</span>
			<!-- <span class="icon is-small is-right">
				<i class="fas fa-check"></i>
			</span> -->
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	</div>

	<div class="field">
		<label class="label">Password</label>
		<div class="control has-icons-left has-icons-right">
			<input required name="password" class="input iss-success" type="password" placeholder="Password" value="">
			<span class="icon is-small is-left">
			<i class="fas fa-lock"></i>
			</span>
			<!-- <span class="icon is-small is-right">
				<i class="fas fa-check"></i>
			</span> -->
		</div>
	<!-- <p class="help is-success">This username is available</p> -->
	</div>

	<h2 class="title">User Groups</h2>
	<p class='help'>At least one group should be selected, but this is not enforced.</p><br>
	<div class="field">
		<?php foreach ($all_groups as $group):?>
		<div class="control">
			<label class="checkbox">
				<input name='groups[]' value='<?php echo $group->id;?>' type="checkbox" <?php if ($group->value=='registered') echo " checked " ?>  >
				<?php echo $group->display;?>
			</label>
		</div>
		<br>
		<?php endforeach; ?>
	</div>

	<div class="control">
		<button type="submit" class="button is-primary">Save</button>
	</div>

</form>