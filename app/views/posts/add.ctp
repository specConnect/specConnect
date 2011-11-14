<?php  
	$html->addCrumb($title[0], $title['link0']);
    $html->addCrumb($title[1], $title['link1']); 
    $html->addCrumb($title[2]); 
    
    $javascript->link('ckeditor/ckeditor', false);
    $javascript->link('ckeditor/adapters/jquery', false);
?>

<script type="text/javascript">
    $(document).ready( function() {
        $('#see').click( function () {
            var contents = $('#editme').val();
            $("#preview").html(contents);
            $("#prevBox").attr('style', 'width:70%;display:block;');
        });

        $('#editme').ckeditor();      
    });
</script>

<div id="inner">
    <?php 
        echo $html->link('Preview', '#preview', array('id' => 'see'));
        if($quote_id != NULL && $quote != NULL) {
            $quote = "Quote:<br /><blockquote>".$quote."</blockquote><br /><br />";
            echo $form->create('Post', array('action'=> "add/". $thread_id ."/".$quote_id."/"));
            echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 'id' => 'editme','value' => $quote));
            echo $form->end('Reply');
        }
        else {
            echo $form->create('Post', array('action'=> "add/". $thread_id ."/"));
            echo $form->input('content', array('label' => 'Message', 'type' => 'textarea', 
            'id' => 'editme'));
            echo $form->end('Reply');
        }
        
	?>
</div>
<div id="prevBox" style="width:70%;display:none;">
    <a name="preview"></a>
    <table>
        <tr>
        <th style="text-align:left;"><?php echo $title[1]; ?></th>
        </tr>
        <tr>
        <td>
            <div id="preview" class="post"></div>
             <br />
             <hr />
             <div class="post">SPEC - Invent Your Future</div>
        </td>
        </tr>
    </table>
</div>