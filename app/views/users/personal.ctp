<?php 
    $html->css('profile_style', 'stylesheet', array('inline' => false));
    $html->addCrumb("Personal Profile"); 
    $javascript->link('jquery.idTabs.js', false);
?>
<div id="inner">
    <div id="tabSelect">
    <ul> 
        <li><a href="#tabs2">Change Password</a></li> 
        <li><a href="#tabs3">Personal Info</a></li> 
    </ul> 
    <br /><br />
        <div id="tabs3" style="display:none;">
            <?php
                echo $form->create('User', array('controller'=> 'users', 'action'=>'personal'));
                echo $form->input('id', array('id' => 'id', 'type' => 'hidden', 'value' => $id));
                echo $form->input('request', array('id' => 'request', 'type' => 'hidden', 'value' => 'pass'));
                echo $form->input('old_password', array('id' => 'old_password', 'type' => 'password', 'label' => 'Current Password'));
                echo $form->input('password', array('id' => 'password', 'type' => 'password', 'label' => 'New Password'));
                echo $form->input('confirm password', array('id' => 'confirm password', 'type' => 'password', 'label' => 'Confirm Password'));
                echo $form->end('Change');
            ?>
        </div>
        <div id="tabs2" style="display:block;">
            <?php
                echo $form->create('User', array('controller'=> 'users', 'action'=>'personal'));
                echo $form->input('id', array('id' => 'id', 'type' => 'hidden', 'value' => $id));
                echo $form->input('request', array('id' => 'request', 'type' => 'hidden', 'value' => 'user'));
                echo $form->input('first_name', array('id' => 'first_name', 'value' => $first_name));
                echo $form->input('last_name', array('id' => 'last_name', 'value' => $last_name));
                echo $form->input('email', array('id' => 'email', 'value' => $email));
                echo $form->end('Edit');
            ?>
        </div>
    </div>
</div>

<script type="text/javascript"> 
    $("#tabSelect ul").idTabs("tabs2"); 
</script>