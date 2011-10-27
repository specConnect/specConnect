<?php
	class ForumsController extends AppController {
		var $name = "Forums";
		var $helpers = array('Form', 'Html', 'Javascript', 'Time');	
		
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
        
        function subscribe($id = NULL) {
            $this->autoRender = false;
            if($id != NULL) {
                $subscript = $this->Forum->ForumSubscription->find('first', array('conditions' => array('forum_id' => $id, 
                'username' => $this->Auth->user('username'))));
                if($subscript == NULL ) {
                    $this->Forum->ForumSubscription->create();
                    if($this->Forum->ForumSubscription->save(array('forum_id' => $id, 'username' => $this->Auth->user('username'),
                    'email' => $this->Auth->user('email'), 'first_name' => $this->Auth->user('first_name')))) {
                        $thread = $this->Forum->Thread->find('all', array('conditions' => array('forum_id' => $id), 'recursive' => 0, 'fields' => array('id')));
                        foreach ($thread as $row) {
                            $this->Forum->Thread->Subscription->deleteAll(array('thread_id' => $row['Thread']['id'], 'username' => $this->Auth->user('username')));
                        }
                        $this->Session->setFlash("Subscription successfull.");
                        $this->redirect("/threads/view/$id/");
                    }
                }
                else {
                    $this->Forum->ForumSubscription->query("DELETE FROM `forum_subscriptions` WHERE `forum_id` = $id AND `username` = '".$this->Auth->user('username')."'");
                    $this->Session->setFlash("Unsubscribed successfully.");
                    $this->redirect("/forums/view/");
                }
            }
            else {
                $this->redirect("/forums/view/");
            }   
        }
        
		function view() {
			$this->layout = 'forum';
			$this->set('title_for_layout', 'specConnect - Forums');
			
			//Title above Breadcrumb
			$this->set('title', 'SPEC Forums');
			$this->loadModel('Thread');
            $this->loadModel('Post');
            
            $forum = $this->Forum->find('all', array('order' => 'category ASC', 'recursive' => 0));
            
            $index = 0;
            foreach ($forum as $row) {
                $forum[$index]['sub'] = 0;
                if($this->Auth->user()) {
                    $subscription = $this->Forum->ForumSubscription->find('first', array('conditions' => array('forum_id' => $row['Forum']['id'],
                    'username' => $this->Auth->user('username'))));
                    if($subscription == NULL) {
                        $forum[$index]['sub'] = 0;
                    }
                    else {
                        $forum[$index]['sub'] = 1;
                    }
                }
                $thread = $this->Thread->find('first', array('conditions' => array('forum_id' => $row['Forum']['id']), 
                'order' => 'modified DESC', 'recursive' => 0));
                if($thread['Thread']['posts'] == 0) {
                    $forum[$index]['Thread'] = $thread['Thread'];
                    $forum[$index]['Post'] = NULL;
                }
                else {
                    $post = $this->Post->find('first', array('conditions' => array('thread_id' => $thread['Thread']['id']), 
                    'order' => 'modified DESC', 'recursive' => 0));
                    $forum[$index]['Thread'] = NULL;
                    $forum[$index]['Post'] = $post['Post'];
                    $forum[$index]['Post']['posts'] = $thread['Thread']['posts'];
                }
                $index++;
            }            
            
			$this->set('forum', $forum);
		}
		
	}
?>