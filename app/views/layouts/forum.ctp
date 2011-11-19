<?php $breadcrumb = $html->getCrumbs(' > ', 'SPEC Forums'); ?>
<html>
<head>
<title><?php echo $title_for_layout; ?></title>
<?php echo $javascript->link('jquery.js'); ?>
<?php echo $javascript->link('custom.user.js'); ?>
<?php echo $html->css('custom.style'); ?>
<?php echo $html->css('cake.generic'); ?>
<?php echo $scripts_for_layout; ?>
</head>
<body>

    <div id="nav">
    <div class="welcome">
    <?php 
        if($online) { //When user is logged in
            echo $userInfo['User']['first_name']." ".$userInfo['User']['last_name'];
        }        
    ?>
    </div>
    <?php
        if($online) { //When user is logged in
            echo $html->link('SPEC Forums', '/forums/view/');
            echo "&nbsp;&nbsp;&nbsp;";
            echo $html->link('Edit Profile', '/profiles/edit/');
            echo "&nbsp;&nbsp;&nbsp;";
            echo $html->link('Personal Info', '/users/personal/');
            echo "&nbsp;&nbsp;&nbsp;";
            echo $html->link('My Forum Posts', '/threads/myposts/');
            echo "&nbsp;&nbsp;&nbsp;";
            echo $html->link('Sign Out', '/users/logout/');
        }
        else {
            echo $html->link('Sign In', '/users/login/') . " or " . $html->link('Register', '/users/register/');
        }
    ?>
    </div>
	<div id="container">
		<div align="center">
			<div id="header"><?php echo $html->link($html->image('specLogo/logo.png'), '/forums/view/', array('escape' => false)); ?></div>
            <?php
                //Breadcrumb
                if($breadcrumb != NULL) {
                    echo "<div id=\"titleCrumb\"> <h1>" . $breadcrumb . "</h1></div><br />";
                }
            ?>
            <div id="content">
				<?php echo $session->flash('auth'); ?>
				<?php echo $session->flash(); ?>				
				<?php echo $content_for_layout; ?>
			</div>
			<div id="footer">
				<?php echo $html->image('specLogo/spec.jpg'); ?>
				<h3>� 2011 SPEC</h3>
			</div>
		</div>
	</div>
</body>
</html>