<?php
    class PostsController extends AppController {
        var $name = 'Posts';
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
        
        function add($id = NULL) {
            $this->layout = 'forum';
            $this->set('title_for_layout', 'specConnect - Add Post');
            $this->loadModel('Thread');
            $this->loadModel('Forum');
            $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'fields' => array('thread_name'), 'recursive' => 0)); 
            //Title above Breadcrumb
            $this->set('title', "POST TO: ".$thread['Thread']['thread_name']);	
            
            if(!empty($this->data) && $id != NULL) {
                //Adding a thread
                $this->data['Post']['username'] = $this->Auth->user('username');
                $this->data['Post']['thread_id'] = $id;
                $this->Post->create(); //Gets ready to create new entry in database
                if($this->Post->save($this->data)) {
                    //Update number of posts in thread
                    $posts = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                    $forum_id = $posts['Thread']['forum_id'];
                    $posts = $posts['Thread']['posts'];
                    $posts = $posts + 1;
                    $this->Thread->save(array('id' => $id, 'posts' => $posts));
                    
                    //Update number for posts in forum and last posting user
                    $posts = $this->Forum->find('first', array('conditions' => array('id' => $forum_id), 'fields' => array('posts'), 'recursive' => 0));
                    $posts = $posts['Forum']['posts'];
                    $posts = $posts + 1;
                    $this->Forum->save(array('id' => $forum_id, 'posts' => $posts, 'lastpost' => $this->Auth->user('username')), false);
                    $this->Session->setFlash("Post added successfully");
                    $post_id = $this->Post->id;
                    $this->redirect(array('controller' => 'posts', 'action' => "view/".$id."#post$post_id/"));
                }
                else {
                    $this->Session->setFlash("Post was not added. Error occured. Try again.");
                }
                
            }
            else if($id == NULL) {
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
            
            $this->set('thread_id', $id);
		}
        
        function view($id = NULL) {
            if($id != NULL) {
                //Set pagination parameters
                $this->paginate = array(
                            'Post' => array(
                                'limit' => 10, 
                                'conditions' => array('thread_id' => $id),
                                'recursive' => 0, 
                                'order' => array('modified ASC')
                            )
                        );
                $this->layout = 'forum';
                $this->set('title_for_layout', 'specConnect - Threads');
                $this->loadModel('Thread');
                $this->loadModel('User');
                $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                $thread_user = $this->User->find('first', array('conditions' => array('username' => $thread['Thread']['username'])));
                $posts = $this->paginate('Post');
                
                $index = 0;
                foreach ($posts as $row) {
                    $post_user = $this->User->find('first', array('conditions' => array('username' => $row['Post']['username']), 'recursive' => 0));
                    $posts[$index]['User'] = $post_user['User'];
                    $index++;
                }
                
                $this->set('title', $thread['Thread']['thread_name']);
                $this->set('thread', $thread);
                $this->set('thread_user', $thread_user);
                $this->set('posts', $posts);
            }
            else {
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
    }
?>