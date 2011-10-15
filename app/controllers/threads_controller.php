<?php 
	class ThreadsController extends AppController {
		var $paginate; 
        var $name = 'Threads';
		var $helpers = array('Form', 'Html', 'Javascript', 'Time');

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
			parent::beforeFilter();
			$this->Auth->allow('view');
			
			$online = false;
			if($this->Auth->user()) {
				$online = true;
				$this->set('userInfo', $this->Auth->user());
			}
			$this->set('online', $online); 
		}
		
        function delete($id = NULL, $f_id = NULL) {
            $this->autoRender = false;
            $user = $this->Auth->user('username');
            $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
            if($user == $thread['Thread']['username'] || $this->__isAdmin()) {
                if($id != NULL) {
                    $this->loadModel('Post');
                    $this->loadModel('Forum');
                    
                    //Delete all Threads, and Posts attached to Threads
                    $this->Thread->delete($id, false);
                    $this->Post->deleteAll(array('thread_id' => $id));
                    
                    $forum = $this->Forum->find('first', array('conditions' => array('id' => $f_id), 'recursive' => 0));
                    if($forum['Forum']['threads'] > 0) {
                        $forum['Forum']['threads'] = $forum['Forum']['threads'] - 1;
                        $this->Forum->save(array('id' => $f_id, 'threads' => $forum['Forum']['threads']), false);
                    }
                    
                    $this->Session->setFlash('Thread deleted successfully.');
                    $this->redirect("/threads/view/$f_id");
                }
                else {
                    $this->redirect(array('controller' => 'forums', 'action' => 'view'));
                }
            }
            else {
                $this->Session->setFlash('You cannot delete a post which does not belong to you.');
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
        
		function add($id = NULL) {
				$this->layout = 'forum';
				$this->set('title_for_layout', 'specConnect - Add Thread');
					
				//Title above Breadcrumb
				$this->set('title', 'Add New Thread');	
                
				if(!empty($this->data) && $id != NULL) {
                    //Adding a thread
					$this->data['Thread']['username'] = $this->Auth->user('username');
                    $this->data['Thread']['forum_id'] = $id;
					if($this->Thread->save($this->data)) {
                        $this->loadModel('Forum');
                        //Update number for threads in forum and last posting user
                        $threads = $this->Forum->find('first', array('conditions' => array('id' => $id), 'fields' => array('threads'), 'recursive' => 0));
                        $threads = $threads['Forum']['threads'];
                        $threads = $threads + 1;
                        $this->Forum->save(array('id' => $id, 'threads' => $threads, 'lastpost' => $this->Auth->user('username')), false);
                        $this->Session->setFlash("Thread added successfully");
                        $this->redirect(array('controller' => 'threads', 'action' => "view/".$id."/"));
                    }
                    else {
                        $this->Session->setFlash("Thread was not added. Error occured. Try again.");
                    }
                    
				}
				else if($id == NULL) {
					$this->redirect(array('controller' => 'forums', 'action' => 'view'));
				}
                
                 $this->set('forum_id', $id);
		}
		
		function view($id = NULL) {
            $this->set('loggedUser', $this->Auth->user('username'));
            $this->set('admin', $this->__isAdmin());
            $this->set('sadmin', $this->__isSuperAdmin());
			if($id == NULL) {
				$this->redirect(array('controller' => 'forums', 'action' => 'view'));
			}
			else {
                //Set pagination parameters
                $this->paginate = array(
                            'Thread' => array(
                                'limit' => 10, 
                                'conditions' => array('forum_id' => $id),
                                'recursive' => 0, 
                                'order' => array('sticky DESC','modified DESC')
                            )
                        );
                        
				$this->layout = 'forum';
				
				$this->set('title_for_layout', 'specConnect - Threads');
				
				//Load up Forum Model so that we can get some information about the forum
				$this->loadModel('Forum');
				$this->loadModel('User');
                $this->loadModel('Post');
                
				$forum = $this->Forum->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
				$thread = $this->paginate('Thread');
			    
                $index = 0;
                foreach ($thread as $row) {
                    $post = $this->Post->find('first', array('conditions' => array('thread_id' => $row['Thread']['id']), 'order' => array('modified DESC')));
                    $thread[$index]['Post'] = $post['Post'];
                    $index++;
                }
                
				//Title above Breadcrumb
				$this->set('title', $forum['Forum']['name']);
				
				$this->set('forum', $forum);
				$this->set('thread', $thread);
			}
		}
        
        function sticky($id = NULL, $f_id = NULL) {
            $this->autoRender = false;
            if($this->__isSuperAdmin() || $this->__isAdmin()) {
                if($id != NULL && $f_id != NULL) {
                    $sticky = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                    $sticky = $sticky['Thread']['sticky'];
                    $sticky = $sticky ? 0 : 1;
                    if($this->Thread->save(array('id' => $id, 'sticky' => $sticky), false)) {
                        $this->Session->setFlash('Thread has been updated.');
                        $this->redirect("/threads/view/$f_id/");
                    }
                    else {
                        $this->Session->setFlash('Sorry, this task cannot be performed.');
                        $this->redirect("/forums/view");
                    }
                }
                else {
                    $this->Session->setFlash('Sorry, request was not understood.');
                    $this->redirect('/forums/view');
                }
            }
            else {
                $this->Session->setFlash('Sorry, you do not have appropriate permissions.');
                $this->redirect("/forums/view/");
            }
        }
	}
?>