<?php 
    $html->addCrumb("Search"); 
    echo $js->writeBuffer(array('cache' => true));
    
    echo $form->create('Thread');
    echo $form->input('search', array('id' => 'search', 'value' => 'Search Text Here', 'label' => ''));
    echo $form->end('Search');
    
    echo $js->link("AJAX REQUEST", "/threads/search", array('update' => 'abcd'));
?>

<div id="abcd">
    <?php echo $hello; ?>
</div>