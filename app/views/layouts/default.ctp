<html>
<head>
<title><?php echo $title_for_layout; ?></title>
<?php echo $html->script('jquery.js'); ?>
<?php echo $html->css('cake.generic'); ?>
<?php echo $scripts_for_layout; ?>
<style>
    #header {
        margin:0px;
        padding-top:10px;
    }
</style>
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
				<h3>© 2012 SPEC</h3>
			</div>
		</div>
	</div>
</body>
</html>