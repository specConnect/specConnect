<?php echo $html->script('jquery', FALSE); ?>
<h2>Create an Account</h2>

<?php echo $session->flash('auth'); ?>
<br />
Your account will allow you to post on specConnect. If you already have an account with specConnect, 
you can <?php echo $html->link('sign in here', '/users/login');?>.

<div id="inner">
	<br /><br />
	<?php 
		echo $form->create('User', array('action'=>'register'));
		echo $form->input('username', array('id' => 'username'));
		echo $form->input('password', array('type' => 'password', 'id' => 'password'));
		echo $form->input('confirm password', array('type' => 'password', 'id' => 'confirm password'));
		echo $form->input('first_name', array('id' => 'first_name'));
		echo $form->input('last_name', array('id' => 'last_name'));
		echo $form->input('email', array('id' => 'email'));
		echo $form->input('confirm email', array('id' => 'confirm email'));
		echo $form->end('Create an Account');
	?>
</div>