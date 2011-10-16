<?php  
	$html->addCrumb("Edit Thread");
    $javascript->link('ckeditor/ckeditor', false);
?>
<div id="inner">
	<?php 
         if($thread_name == NULL && $content == NULL) {
            echo $form->create('Thread', array('action'=> "edit/". $id ."/"));
            echo $form->input('thread_name', array('id' => 'thread_name'));
            echo $form->input('content', array('type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor'));
            echo $form->end('Finish');
        }
        else {
            echo $form->create('Thread', array('action'=> "edit/". $id ."/"));
            echo $form->input('thread_name', array('id' => 'thread_name', 'value' => $thread_name));
            echo $form->input('content', array('type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor', 'value' => $content));
            echo $form->end('Finish');
        }
	?>
</div>