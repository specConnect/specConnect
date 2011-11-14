<?php 
	class Thread extends AppModel {
		var $name = 'Thread';
		
        var $hasMany = array(
            'Post' => array(
                'className' => 'Post'
            ),
            'ThreadView' => array(
                'className' => 'ThreadView'
            ),
			'Subscription' => array(
				'className' => 'Subscription'
			),
            'Thumb' => array(
                'className' => 'Thumb'
            )
		);
        
		var $validate = array(
			'thread_name' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => 'A thread with this name already exists.'
					),
                'between' => array(
					'rule' => array('between', 5, 60),
					'message' => 'The username has to be between 5 to 60 characters in length'
					),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please fill out a name for your thread'
					),
				'minLength' => array(
					'rule' => array('minLength', 5),
					'message' => 'Title has to have a minimum of 5 characters'
				)
			),
			'content' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please add some content to your thread.'
					),
				'minLength' => array(
					'rule' => array('minLength', 5),
					'message' => 'Title has to have a minimum of 5 characters'
				)
			)
		);
	}
?>