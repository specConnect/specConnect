<?php 
    class ProfilesController extends AppController {
        var $name = "Profiles";
		var $helpers = array('Form', 'Html', 'Javascript', 'Time');	
		
		function beforeFilter() {
			parent::beforeFilter(); 
			
			$online = false;
			if($this->Auth->user()) {
				$online = true;
				$this->set('userInfo', $this->Auth->user());
			}
			$this->set('online', $online);
		}
        
        function edit() {
            $this->layout = 'forum';
            $this->set('title_for_layout', 'specConnect - Edit Profile');
            $this->set('title', "Edit Profile"); 
            if(!empty($this->data)) {
                $this->data['Profile']['user_id'] = $this->Auth->user('id');
                if($this->Profile->save($this->data)) {
                    $profile = $this->Profile->find('first', array('conditions' => array('user_id' => $this->Auth->user('id')), 'recursive' => 0));
                    $this->Session->setFlash("Changes Saved");
                }
                else {
                    $profile = $this->Profile->find('first', array('conditions' => array('user_id' => $this->Auth->user('id')), 'recursive' => 0));
                    $this->Session->setFlash("Error Saving Changes");
                    $profile['Profile']['university_program'] = NULL;
                    $profile['Profile']['signature'] = NULL;
                }
            }
            else {
                $profile = $this->Profile->find('first', array('conditions' => array('user_id' => $this->Auth->user('id')), 'recursive' => 0));
            }
            $this->set('avatar', $this->Auth->user('avatar'));
            $this->set('profile', $profile);
        }
    }
?>