<?php 
	$html->addCrumb("Add New Thread");
?>
<div id="inner">
	<?php 
		echo $form->create('Thread', array('action'=> "add/". $forum_id ."/"));
		echo $form->input('thread_name', array('id' => 'thread_name'));
		echo $form->textarea('content', array('id' => 'content'));
		echo $form->end('Create Thread');
	?>
</div>