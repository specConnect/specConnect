<?php  
	$html->addCrumb("Add New Thread");
    $javascript->link('ckeditor/ckeditor', false);
?>
<div id="inner">
	<?php 
		echo $form->create('Thread', array('action'=> "add/". $forum_id ."/"));
		echo $form->input('thread_name', array('id' => 'thread_name'));
        if($sadmin) {
            echo $form->input('private', array('type' => 'checkbox', 'label' => 'SPEC Executives Only'));
        }
		echo $form->input('content', array('type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor'));
		echo $form->end('Create Thread');
	?>
</div>