<?php
    class Profile extends AppModel {
        var $name = "Profile";
        var $belongsTo = "User";
        
        var $validate = array(
			'university' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
					)
			),
			'university_program' => array(
				'minLength' => array(
					'rule' => array('minLength', '5'),
					'message' => 'This field has to be atleast 5 characters long'
				),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
				),
			),
			'signature' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
				)
			)
		);
    }
?>