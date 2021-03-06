<?php 
defined('CMSPATH') or die; 
// prevent unauthorized access 
require_once (CMSPATH . "/core/cms.php");
?>

<html>
<meta name="viewport" content="width=device-width, user-scalable=no" />
	<head><!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo Config::$uripath;?>/admin/templates/clean/css/bulma.min.css"></link>
<link rel="stylesheet" href="<?php echo Config::$uripath;?>/admin/templates/clean/css/dashboard.css"></link>
<link rel="stylesheet" href="<?php echo Config::$uripath;?>/admin/templates/clean/css/layout.css"></link>

<script src="https://kit.fontawesome.com/e73dd5d55b.js" crossorigin="anonymous"></script>


		</head>
		<body>

			<nav class="navbar container" role="navigation" aria-label="main navigation">
				<div class="navbar-brand">
					<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/">
					<img src="https://via.placeholder.com/112x28?text=HBCMS" width="112" height="28">
					</a>

					<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					</a>
				</div>

				<div id="navbarBasicExample" class="navbar-menu">
					<div class="navbar-start">
						<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/">Home</a>

						<?php if (CMS::Instance()->user->is_member_of('admin')):?>
						<div class="navbar-item has-dropdown is-hoverable">
							<a class="navbar-link">Users</a>
							<div class="navbar-dropdown">
								<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/users">All Users</a>
								<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/users/options">User Options</a>
							</div>
						</div>
						<?php endif; ?>

						<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/pages/">Pages</a>
						
						<div class="navbar-item has-dropdown is-hoverable">
							<a class="navbar-link">Content</a>
							<div class="navbar-dropdown">
								<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/content/all">All Content</a>
								<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/content/types">Content Types</a>
							</div>
						</div>

						<!--<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/controllers/all">Controllers</a>-->
						<div class="navbar-item has-dropdown is-hoverable">
							<a class="navbar-link">Widgets</a>
							<div class="navbar-dropdown">
								<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/widgets/show/">All Widgets</a>
								<?php foreach (Widget::get_all_widget_types() as $widget_type):?>
								<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/widgets/show/<?php echo $widget_type->id; ?>"><?php echo $widget_type->title; ?></a>
								<?php endforeach; ?>
							</div>
						</div>
						<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/tags">Tags</a>
						<a class="navbar-item" href="<?php echo Config::$uripath;?>/admin/media">Media</a>

					
					</div>

					<div class="navbar-end">
					<div class="navbar-item">
						<div class="buttons">

						<a href="<?php echo Config::$uripath;?>/admin/logout.php" class="button is-light">
							Log Out <?php echo CMS::Instance()->user->username; ?>
						</a>
						</div>
					</div>
					</div>
				</div>
			</nav>
		


    <section id="main">
      <div class="container">

	
	  		<?php CMS::Instance()->display_messages();?>
        <!-- <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="/admin/home">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="/admin/content">Content</a></li>
            <li><a href="/admin/users">Users</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item</a></li>
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
            <li><a href="">More navigation</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
          </ul>
        </div>-->
        <!--<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">-->
       
		  <?php CMS::Instance()->render_controller();?>

		<?php 
		if (Config::$debug) {
			echo "<h1>Debug FYI</h1>";
			CMS::showinfo();
		} ?>
       
      </div>
    </div>

</body>
</html>


