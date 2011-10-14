<?php  
	$html->addCrumb("Add New Post");
    $javascript->link('ckeditor/ckeditor', false);
?>
<div id="inner">
    <?php 
        echo $form->create('Post', array('action'=> "add/". $thread_id ."/"));
		echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor'));
		echo $form->end('Create Post');
	?>
</div>