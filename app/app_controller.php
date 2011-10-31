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
            switch ($posts) {
                case ($posts >= 20):
                    $rating = "2nd Year";
                    break;
                case ($posts >= 40):
                    $rating = "3rd Year";
                    break;
                case ($posts >= 70):
                    $rating = "4th Year";
                    break;
                case ($posts >= 100):
                    $rating = "Undergraduate";
                    break;
                case ($posts >= 150):
                    $rating = "Masters-MSc";
                    break;
                case($posts >= 250):
                    $rating = "Doctorate - PHd.";
                    break;
                default:
                    $rating = NULL;
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
        }
        
		function beforeFilter() {
			$this->Auth->authError = 'Please login to view that page';
			$this->Auth->loginError = 'Incorrect username/password combination';
			$this->Auth->loginRedirect = array('controller' => 'forums', 'action' => 'view');
			$this->Auth->logoutRedirect = array('controller' => 'forums', 'action' => 'view');
		}
	}
?>