<?php 
	class User extends AppModel {
		var $name = "User";
        var $hasOne = "Profile";
        
		var $validate = array(
			'username' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message'=> 'This username already exists'
				),
				'between' => array(
					'rule' => array('between', 5, 15),
					'message' => 'The username has to be between 5 to 15 characters in length'
					),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
					)
			),
			'password' => array(
				'minLength' => array(
					'rule' => array('minLength', '5'),
					'message' => 'Password has to be atleast 5 characters long'
				),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
				),
				'checkPass' => array(
					'rule' => 'checkPass',
					'message' => 'Passwords do not match'
				)
			),
			'first_name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
				)
			),
			'last_name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
				)
			),
			'email' => array(
				'email' => array(
					'rule' => 'email',
					'message' => 'Enter a valid email address'
				),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'This field cannot be left empty'
				),
				'checkEmail' => array(
					'rule' => 'checkEmail',
					'message' => 'Email addresses do not match'
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message'=> 'This email address already exists'
				)
			)
		);
		
		function checkPass($data) {
			if($data['password'] == $this->data['User']['confirm password']) {
				return TRUE;
			}
			$this->invalidate('confirm password', 'Passwords do not match');
			return FALSE;
		}
		
		function checkEmail($data) {
			if($data['email'] == $this->data['User']['confirm email']) {
				return TRUE;
			}
			$this->invalidate('confirm email', 'Email addresses do not match');
			return FALSE;
		}
		
		function hashPasswords($data) {
			if(isset($this->data['User']['password'])){
				$this->data['User']['password'] = Security::hash($this->data['User']['password'], NULL, TRUE);
				return $data;
			}
			return $data;
		}
		
		function beforeSave() {
            if($this->data != NULL) {
                $this->data['User']['first_name'] = ucwords(strtolower($this->data['User']['first_name']));
                $this->data['User']['last_name'] = ucwords(strtolower($this->data['User']['last_name']));
                $this->hashPasswords($this->data);
            }
			return TRUE;
		}
	}
?>