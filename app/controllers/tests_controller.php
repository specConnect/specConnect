<?php
    class TestsController extends AppController {
        var $name = "Tests";
        var $uses = null;
        var $helpers = array('Form', 'Html', 'Paging');	
        
        function beforeFilter() {
            parent::beforeFilter();
            Zend_Loader::loadClass('Zend_Gdata_Docs');
            Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
        }
        
        function google_doc() {
            $this->set('title_for_layout', 'Zend Gdata Docs Test');
            $this->set("array", array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V",
                                       "W","X","Y","Z"));
        }   
    }
?>