<?php 
    $html->addCrumb("Manage Profile"); 
    $javascript->link('ckeditor/ckeditor', false);
?>
<div id="inner">
    Post Rating: <b><i><?php echo $profile['Profile']['rating']; ?></i></b><br /><br />
    <?php echo $html->image($avatar); ?>
    <br /><br />
    <h1><i>specConnect uses <b>Gravatar</b></i><br /> 
    To create a gravatar, or to change you gravatar click <a href="http://en.gravatar.com/site/login/" target="_blank">here</a></h1>
    <br /><br />
	<?php 
        $options = array(
                    'Simon Fraser University' => 'Simon Fraser University',
                    'University of British Columbia' => 'University of British Columbia',
                    'University of Victoria' => 'University of Victoria',
                    'Trinity Western University' => 'Trinity Western University',
                    'University of Northern British Columbia' => 'University of Northern British Columbia',
                    'British Columbia Institute of Technology' => 'British Columbia Institute of Technology',
                    'Thomson River University' => 'Thomson River University'
        );
		echo $form->create('Profile', array('action'=>'edit'));
        
        if($profile['Profile']['university_program'] == NULL || $profile['Profile']['signature'] == NULL) {
            echo $form->select('university', $options, $profile['Profile']['university'], array('id' => 'university')) . "<br /><br />";
        	echo $form->input('university_program', array('id' => 'university_program'));
            echo $form->input('signature', array('type' => 'textarea', 'id' => 'signature', 'class' => 'ckeditor'));
        }
        else {
            echo $form->select('university', $options, $profile['Profile']['university'], array('id' => 'university')) . "<br /><br />";
        	echo $form->input('university_program', array('id' => 'university_program', 'value' => $profile['Profile']['university_program']));
            echo $form->input('signature', array('type' => 'textarea', 'id' => 'signature', 'class' => 'ckeditor', 'value' => $profile['Profile']['signature']));

        }
        echo $form->input('id', array('id' => 'id', 'type' => 'hidden', 'value' => $profile['Profile']['id']));
		echo $form->end('Save Changes');
	?>
</div>