<h2>Welcome Guest</h2>

<?php echo $session->flash('auth'); ?>

<div id="inner">
	<?php 
		echo $form->create('User', array('action'=>'login'));
		echo $form->input('username');
		echo $form->input('password', array('type' => 'password'));
		echo "New to SPEC? " . $html->link('Register now', '/users/register');
		echo " or  go to " .$html->link('specConnect', '/forums/view');
		echo $form->end('Sign In');
	?>
</div>