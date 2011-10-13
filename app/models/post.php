<?php 
    class Post extends AppModel {
        var $name = 'Post';
        
        var $validate = array(
            'content' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Please add some content to your thread.'
                    ),
                'minLength' => array(
                    'rule' => array('minLength', 20),
                    'message' => 'Title has to have a minimum of 20 characters'
                )
            )
		);
    }
?>