<?php 
	class UsersController extends AppController {
		var $name = "Users";
		var $helpers = array('Form', 'Html', 'Js', 'Time', 'Paging', 'Paginator');

		function beforeFilter() {
			parent::beforeFilter(); 
			$this->Auth->allow('login', 'register');
			
			//Use custom authentication as defined in model User
			if($this->action == 'register') {	
				$this->Auth->authenticate = $this->User;
			}
            
            //Set User Online status
            $online = false;
			if($this->Auth->user()) {
				$online = true;
				$this->set('userInfo', $this->Auth->user());
			}
			$this->set('online', $online); 
            
			//Redirect everytime except when logout or changePass action
			if($online && $this->action != 'logout' && $this->action != 'personal') {
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
		
        function personal() {
            $this->layout = 'forum';
            $this->set('title_for_layout', 'specConnect - Personal Info');
            $id = $this->Auth->user('id');
            $this->set('id', $id);
            if(!empty($this->data)) {
                if($this->data['User']['request'] == "pass") {
                    $this->data['User']['first_name'] = NULL;
                    $this->data['User']['last_name'] = NULL;
                    if($this->User->save($this->data, true, array('password', 'old_password'))) {
                        $this->Session->setFlash("Successfully saved your password.");
                        $this->Session->write('Auth', $this->User->read(null, $id));
                    }
                    else {
                        $this->Session->setFlash("Error occured, please try again.");
                    } 
                }
                else if($this->data['User']['request'] == "user") {
                    $oldEmail = $this->Auth->user('email');
                    $this->data['User']['confirm email'] = $this->data['User']['email'];
                    if($this->User->save($this->data, true, array('first_name', 'last_name', 'email'))) {
                        $this->Session->write('Auth', $this->User->read(null, $id)); //Rewrite to Auth Session
                        if($this->Auth->user('email') != $oldEmail) { //IF USER CHANGES HIS EMAIL (WE WILL ALSO SEND CONFIRMATION IN FUTURE)
                            $this->loadModel('Forum');
                            $this->loadModel('Thread');
                            $this->Forum->ForumSubscription->updateAll(array('email' => "'".$this->Auth->user('email')."'"), 
                            array('username' => $this->Auth->user('username')));
                            $this->Thread->Subscription->updateAll(array('email' => "'".$this->Auth->user('email')."'"), 
                            array('username' => $this->Auth->user('username')));
                        }
                        $this->Session->setFlash("Successfully updated information.");
                    }
                    else {
                        $this->Session->setFlash("Error occured, please try again.");
                    } 
                }
                else {
                    $this->redirect("/forums/view/");
                }
            }
            $this->set('first_name', $this->Auth->user('first_name'));
            $this->set('last_name', $this->Auth->user('last_name'));
            $this->set('email', $this->Auth->user('email'));
        }
        
		function register() {
			$this->set('title_for_layout', 'specConnect - Register');
			if(!empty($this->data)) {
                $hash = md5(strtolower(trim($this->data['User']['email'])));
                $this->data['User']['avatar'] = "http://www.gravatar.com/avatar/$hash.jpg?s=100&d=identicon";
				if($this->User->save($this->data)) {
                    //Create a new profile for user
                    $this->User->Profile->create();
                    $this->User->Profile->save(array('user_id' => $this->User->id, 'signature' => "SPEC - Invent Your Future"));
                    
                    //Subscribe User to all Forums
                    $this->loadModel('Forum');
                    $forum = $this->Forum->find('all', array('fields'=>array('id')));
                    foreach ($forum as $row):
                        $array = array(
                                'forum_id' => $row['Forum']['id'],
                                'username' => $this->data['User']['username'],
                                'first_name' => $this->data['User']['first_name'],
                                'email' => $this->data['User']['email']
                        );
                        $this->Forum->ForumSubscription->create();
                        $this->Forum->ForumSubscription->save($array);
                    endforeach;
                    
                    
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