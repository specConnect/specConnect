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
		
		function view() {
			$this->layout = 'forum';
			$this->set('title_for_layout', 'specConnect - Forums');
			
			//Title above Breadcrumb
			$this->set('title', 'SPEC Forums');
			$this->loadModel('Thread');
            $this->loadModel('Post');
            
            $forum = $this->Forum->find('all', array('order' => 'category ASC'));
            
            $index = 0;
            foreach ($forum as $row) {
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