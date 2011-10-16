<?php  
	$html->addCrumb("Edit Post");
    $javascript->link('ckeditor/ckeditor', false);
?>
<div id="inner">
	<?php 
         if($content == NULL) {
            echo $form->create('Post', array('action'=> "edit/". $id ."/"));
            echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor'));
            echo $form->end('Finish');
        }
        else {
            echo $form->create('Post', array('action'=> "edit/". $id ."/"));
            echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor', 'value' => $content));
            echo $form->end('Finish');
        }
	?>
</div>