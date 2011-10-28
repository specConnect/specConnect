<?php 
	class UsersController extends AppController {
		var $name = "Users";
		var $helpers = array('Form', 'Html', 'Javascript', 'Time');

		function beforeFilter() {
			parent::beforeFilter(); 
			$this->Auth->allow('login', 'register');
			
			//Use custom authentication as defined in model User
			if($this->action == 'register') {	
				$this->Auth->authenticate = $this->User;
			}
			
			//Redirect everytime except when logout action
			if($this->Auth->user() && $this->action != 'logout') {
				$this->redirect(array('controller' => 'forums', 'action' => 'view'));
			}
		}
		
		function login() {
			$this->set('title_for_layout', 'specConnect');
		}
		
		function logout() {
            $this->autoRender = false;
			$this->redirect($this->Auth->logout());
		}
		
		function register() {
			$this->set('title_for_layout', 'specConnect - Register');
			if(!empty($this->data)) {
                $hash = md5(strtolower(trim($this->data['User']['email'])));
                $this->data['User']['avatar'] = "http://www.gravatar.com/avatar/$hash.jpg?s=100&d=identicon";
				if($this->User->save($this->data)) {
                    //Create a new profile for user
                    $this->User->Profile->create();
                    $this->User->Profile->save(array('user_id' => $this->User->id));
					$this->Session->setFlash('You have successfully joined specNow');
					$this->redirect(array('action' => 'login'));
				}
				else {
					$this->Session->setFlash('Error occured during registering. Please try again.');
				}
			}
		}
	}
?>