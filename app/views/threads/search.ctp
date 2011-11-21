<?php 
    $html->addCrumb("Search"); 
    
    echo $form->create('Thread');
    echo $form->input('search', array('id' => 'search', 'value' => 'Search Text Here', 'label' => ''));
    echo $js->submit('Search', array(
        'update' => '#data', 
        'before' => $this->Js->get('#waiting')->effect('fadeIn'),
        'success' => $this->Js->get('#waiting')->effect('fadeOut')
    )
    );
?>
<div id="waiting" style="display:none;">
    <?php echo $html->image("other/loading2.gif"); ?>
</div>

<div id="data"></div>