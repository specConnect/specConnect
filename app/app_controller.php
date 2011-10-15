<?php
	class AppController extends Controller {
		var $components = array('Auth', 'Session');	
        
        function __isAdmin() {
            $this->loadModel('User');
            $user = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id')), 'recursive' => 0));
            return ($user['User']['roles'] == 'admin' || $user['User']['roles'] == 'sadmin');
        }
        
        function __isSuperAdmin() {
           $this->loadModel('User');
           $user = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id')), 'recursive' => 0));
           return ($user['User']['roles'] == 'sadmin');
        }
        
		function beforeFilter() {
			$this->Auth->authError = 'Please login to view that page';
			$this->Auth->loginError = 'Incorrect username/password combination';
			$this->Auth->loginRedirect = array('controller' => 'forums', 'action' => 'view');
			$this->Auth->logoutRedirect = array('controller' => 'forums', 'action' => 'view');
		}
	}
?>