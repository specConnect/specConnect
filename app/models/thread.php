<?php 
	class Thread extends AppModel {
		var $name = 'Thread';
		
        var $hasMany = array(
			'Post' => array(
				'className' => 'Post',
				'order' => array('modified ASC')
			)
		);
        
		var $validate = array(
			'thread_name' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => 'A thread with this name already exists.'
					),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please fill out a name for your thread'
					),
				'minLength' => array(
					'rule' => array('minLength', 10),
					'message' => 'Title has to have a minimum of 10 characters'
				)
			),
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