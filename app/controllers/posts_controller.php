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
        
        function view($id = NULL) {
            if($id != NULL) {
                $this->layout = 'forum';
                $this->set('title_for_layout', 'specConnect - Threads');
                $this->loadModel('Thread');
                $this->loadModel('User');
                $thread = $this->Thread->find('first', array('conditions' => array('id' => $id)));
                $thread_user = $this->User->find('first', array('conditions' => array('username' => $thread['Thread']['username'])));
                $posts = $thread['Post'];
                
                $index = 0;
                foreach ($posts as $row) {
                    $post_user = $this->User->find('first', array('conditions' => array('username' => $posts[$index]['username']), 'recursive' => 0));
                    $posts[$index]['Post'] = $post_user;
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