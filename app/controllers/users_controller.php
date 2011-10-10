<?php 
	class UsersController extends AppController {
		var $name = "Users";
		
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
			$this->redirect($this->Auth->logout());
		}
		
		function register() {
			$this->set('title_for_layout', 'specConnect - Register');
			if(!empty($this->data)) {
				if($this->User->save($this->data)) {
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