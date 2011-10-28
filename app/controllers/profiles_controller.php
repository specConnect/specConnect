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
        
        function view($id = NULL) {
            $this->set('title_for_layout', 'specConnect - View Profile');
            if($id != NULL) {
                
            }
            else {
                $this->redirect("/forums/view/");
            }
        }
    }
?>