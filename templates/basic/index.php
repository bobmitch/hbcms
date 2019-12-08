<?php
	defined('CMSPATH') or die; // prevent unauthorized access
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo CMS::Instance()->page->title;?></title>
</head>
<body>
	<h1>Clean Template</h1>
	<h5>start content</h5>
	<hr>
	<?php CMS::Instance()->render_controller(); ?>
	<hr>
	<h5>end content</h5>
	<?php if (Config::$debug) { CMS::Instance()->showinfo();} ?>
</body>
</html>