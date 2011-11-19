<?php
	class AppController extends Controller {
		var $components = array('Auth', 'Session', 'RequestHandler', 'Email');
        
        function __isAdmin() {
            $this->loadModel('User');
            $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'recursive' => 0));
            return ($user['User']['roles'] == 'admin' || $user['User']['roles'] == 'sadmin');
        }
        
        function __isSuperAdmin() {
           $this->loadModel('User');
           $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'recursive' => 0));
           return ($user['User']['roles'] == 'sadmin');
        }
        
        function __getSaveRating($user_id, $posts) {
            $this->loadModel('User');
            $rating = NULL;
            switch ($posts) {
                case 20:
                    $rating = "Sophomore(level 2)";
                    break;
                case 80:
                    $rating = "Junior(level 3)";
                    break;
                case 180:
                    $rating = "Senior(level 4)";
                    break;
                case 280:
                    $rating = "BASC(level 5)";
                    break;
                case 400:
                    $rating = "MSc(level 6)";
                    break;
                case 600:
                    $rating = "PHd(level 7)";
                    break;
            }
            if($rating != NULL){
                $this->User->Profile->query("UPDATE `profiles` SET  `rating` = '".$rating."' WHERE `user_id` = ".$user_id.";");
            }
        }
        
        /*
        $model: The name of the first parameter of array
        $field: The field in the array to search
        $value: The value to search.
        $array: The array to search.
        */
        function __find($array = array(), $model, $field, $value) {
            foreach ($array as $row) {
                if($row[$model][$field] == $value) {
                    return $row;
                }
            }
            return NULL;
        }
        
		function beforeFilter() {
			$this->Auth->authError = 'Please login to view that page';
			$this->Auth->loginError = 'Incorrect username/password combination';
			$this->Auth->loginRedirect = array('controller' => 'forums', 'action' => 'view');
			$this->Auth->logoutRedirect = array('controller' => 'forums', 'action' => 'view');
            
            //Getting Gdata API imported for use in all controllers
            App::import('Vendor', 'zend_include_path');
            App::import('Vendor', 'Zend_Gdata', true, false, 'Zend/Gdata.php');
		}
	}
?>