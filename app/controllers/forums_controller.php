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
			
			$this->set('forum', $this->Forum->find('all', array('order' => 'category ASC')));
		}
		
	}
?>