<?php $breadcrumb = $html->getCrumbs(' > ', 'SPEC Forums'); ?>
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
				<h2>
					Welcome, 
					<?php 
						if($online) { //When user is logged in
							echo $userInfo['User']['first_name'];
						}
						else {
							echo "Guest";
						}
					?>
				</h2>
					<?php
						if($online) { //When user is logged in
                            echo $html->link('SPEC Forums', '/forums/view/');
                            echo "&nbsp;&nbsp;&nbsp;";
                            echo $html->link('Edit Profile', '/profiles/edit/');
                            echo "&nbsp;&nbsp;&nbsp;";
							echo $html->link('Sign Out', '/users/logout/');
						}
						else {
							echo $html->link('Sign In', '/users/login/') . " or " . $html->link('Register', '/users/register/');
						}
					?>

				<br /><br />
				<div id="titleCrumb"> 
					<?php
						echo "<h2>" . strtoupper($title) . "</h2>";
						//Breadcrumb
						if($breadcrumb != NULL) {
							echo "<h1>" . $breadcrumb . "</h1>";
						}
					?>
				</div>
				<br />
				<?php echo $session->flash('auth'); ?>
				<?php echo $session->flash(); ?>				
				<?php echo $content_for_layout; ?>
			</div>
			<div id="footer">
				<?php echo $html->image('specLogo/spec.jpg'); ?>
				<h3>© 2011 SPEC</h3>
			</div>
		</div>
	</div>
</body>
</html>