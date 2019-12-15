<?php defined('CMSPATH') or die; // prevent unauthorized access ?>

<html>
<meta name="viewport" content="width=device-width, user-scalable=no" />
	<head><!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo Config::$uripath;?>/admin/templates/clean/css/bulma.min.css"></link>
<link rel="stylesheet" href="<?php echo Config::$uripath;?>/admin/templates/clean/css/dashboard.css"></link>
<link rel="stylesheet" href="<?php echo Config::$uripath;?>/admin/templates/clean/css/layout.css"></link>

<script src="https://kit.fontawesome.com/e73dd5d55b.js" crossorigin="anonymous"></script>

		<style>
		#login.container {
			height:100vh;
			display:flex;
			align-items:center;
			justify-content: center;
		}
		form {
			max-width:30em;
		}
		</style>
		</head>
		<body>
		<div class='container'>
			<?php CMS::display_messages();?>
		</div>
		<div id="login" class='container '>
			
			<form class="" submit="" method="POST">

				<h1 class='title is-1'>HBCMS</h1>
				<div class='field'>
					<label class="label" for='username'>Username</label>
					<div class="control">
						<input autocapitalize="none" type="username" name="username" required>
					</div>
					<p class="help">Required</p>
				</div>
				
				<div class='field'>
					<label class="label" for='password'>Password</label>
					<div class="control">
						<input type="password" name="password" required>
					</div>
					<p class="help">Required</p>
				</div>

				<button class="button is-primary" type="submit">Log In</button>
			</form>
		</div>
		</body>
		</html>