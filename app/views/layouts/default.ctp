<html>
<head>
<title><?php echo $title_for_layout; ?></title>
<?php echo $javascript->link('jquery.js'); ?>
<?php echo $html->css('cake.generic'); ?>
<?php echo $scripts_for_layout; ?>
</head>
<body>
	<div id="container">
		<div align="center">
			<div id="header"><?php echo $html->link($html->image('specLogo/logo.png'), '/forums/view/', array('escape' => false)); ?></div>
			<div id="content">
				<?php echo $content_for_layout; ?>
				<?php echo $session->flash(); ?>
			</div>
			<div id="footer">
				<?php echo $html->image('specLogo/spec.jpg'); ?>
				<h3>© 2011 SPEC</h3>
			</div>
		</div>
	</div>
</body>
</html>