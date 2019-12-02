<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>
<h1 class='title is-1'>
	All Users
	<a href='<?php echo Config::$uripath . "/admin/users/new"?>' class="button is-primary pull-right">
		<span class="icon is-small">
      		<i class="fas fa-check"></i>
    	</span>
		<span>New User</span>
	</a>
</h1>
<table class="table">
	<thead>
		<th>Status</th>
		<th>Name</th>
		<th>Email</th>
		<th>Group(s)</th>
		<th>Created</th>
		<th>ID</th>
	</thead>
	<tbody>
		<?php foreach($all_users as $user):?>
		<tr>
			<td>
				<?php echo $user->state; ?>
			</td>
			<td>
				<?php echo $user->username; ?>
			</td>
			<td>
				<?php echo $user->email; ?>
			</td>
			<td>
				Groups
			</td>
			<td>
				<?php echo $user->created; ?>
			</td>
			<td>
				<?php echo $user->id; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>