<?php  
	$html->addCrumb($title[0], $title['link0']);
    $html->addCrumb($title[1]);
    
    $html->script('ckeditor/ckeditor', array('inline' => false));
    $html->script('ckeditor/adapters/jquery', array('inline' => false));
?>
<script type="text/javascript">
    $(document).ready( function() {
        $('#see').click( function () {
            var thread = $("#thread_name").val();
            if(thread == "") {
                thread = "THREAD TITLE HERE"
            }
            var contents = $('#editme').val();
            $("#thisTitle").html(thread);
            $("#preview").html(contents);
            $("#prevBox").attr('style', 'width:70%;display:block;');
        });

        $('#editme').ckeditor();      
    });
</script>
<div id="inner">
	<?php 
        echo $html->link('Preview', '#preview', array('id' => 'see'));
		echo $form->create('Thread', array('action'=> "add/". $forum_id ."/"));
		echo $form->input('thread_name', array('id' => 'thread_name'));
        if($sadmin) {
            echo $form->input('private', array('type' => 'checkbox', 'label' => 'SPEC Executives Only'));
        }
		echo $form->input('content', array('type' => 'textarea', 'id' => 'editme'));
		echo $form->end('Create Thread');
	?>
</div>
<div id="prevBox" style="width:70%;display:none;">
    <a name="preview"></a>
    <table>
        <tr>
        <th style="text-align:left;"><div id="thisTitle"></div></th>
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