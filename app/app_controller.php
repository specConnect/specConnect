<?php
	class AppController extends Controller {
		var $components = array('Auth', 'Session');	
		
		function beforeFilter() {
			$this->Auth->authError = 'Please login to view that page';
			$this->Auth->loginError = 'Incorrect username/password combination';
			$this->Auth->loginRedirect = array('controller' => 'forums', 'action' => 'view');
			$this->Auth->logoutRedirect = array('controller' => 'forums', 'action' => 'view');
		}
	}
?>