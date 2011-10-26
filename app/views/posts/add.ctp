<?php  
	$html->addCrumb("Add New Post");
    $javascript->link('ckeditor/ckeditor', false);
?>
<div id="inner">
    <?php 
        if($quote_id != NULL && $quote != NULL) {
            $quote = "Quote:<br /><blockquote>".$quote."</blockquote><br /><br />";
            echo $form->create('Post', array('action'=> "add/". $thread_id ."/".$quote_id."/"));
            echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor','value' => $quote));
            echo $form->end('Reply');
        }
        else {
            echo $form->create('Post', array('action'=> "add/". $thread_id ."/"));
            echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 'id' => 'content', 'class' => 'ckeditor'));
            echo $form->end('Reply');
        }
	?>
</div>