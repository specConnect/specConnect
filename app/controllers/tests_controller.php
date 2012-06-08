<?php
    class TestsController extends AppController {
        var $name = "Tests";
        var $uses = null;
        var $helpers = array('Form', 'Html', 'Js', 'Time', 'Paging', 'Paginator');	
        
		/**
		* Retrieve the current URL so that the AuthSub server knows where to
		* redirect the user after authentication is complete.
		*/
		function __getCurrentUrl()
		{
			global $_SERVER;
		 
			// Filter php_self to avoid a security vulnerability.
			$php_request_uri =
				htmlentities(substr($_SERVER['REQUEST_URI'],
									0,
									strcspn($_SERVER['REQUEST_URI'], "\n\r")),
									ENT_QUOTES);
		 
			if (isset($_SERVER['HTTPS']) &&
				strtolower($_SERVER['HTTPS']) == 'on') {
				$protocol = 'https://';
			} else {
				$protocol = 'http://';
			}
			$host = $_SERVER['HTTP_HOST'];
			if (!empty($_SERVER['HTTP_PORT']) &&
				(($protocol == 'http://' && $_SERVER['HTTP_PORT'] != '80') ||
				($protocol == 'https://' && $_SERVER['HTTP_PORT'] != '443'))) {
				$port = ':' . $_SERVER['HTTP_PORT'];
			} else {
				$port = '';
			}
			return $protocol . $host . $port . $php_request_uri;
		}
		 
		/**
		* Obtain an AuthSub authenticated HTTP client, redirecting the user
		* to the AuthSub server to login if necessary.
		*/
		function __getAuthSubHttpClient()
		{
			global $_SESSION, $_GET;
		 
			// if there is no AuthSub session or one-time token waiting for us,
			// redirect the user to the AuthSub server to get one.
			if (!isset($_SESSION['sessionToken']) && !isset($_GET['token'])) {
				// Parameters to give to AuthSub server
				$next = $this->__getCurrentUrl();
				$scope = "http://www.google.com/calendar/feeds/";
				$secure = false;
				$session = true;
		 
				// Redirect the user to the AuthSub server to sign in
				$authSubUrl = Zend_Gdata_AuthSub::getAuthSubTokenUri($next,
																	 $scope,
																	 $secure,
																	 $session);
				 header("HTTP/1.0 307 Temporary redirect");
				 header("Location: " . $authSubUrl);
		 
				 exit();
			}
		 
			// Convert an AuthSub one-time token into a session token if needed
			if (!isset($_SESSION['sessionToken']) && isset($_GET['token'])) {
				$_SESSION['sessionToken'] =
					Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
			}
		 
			// At this point we are authenticated via AuthSub and can obtain an
			// authenticated HTTP client instance
		 
			// Create an authenticated HTTP client
			$client = Zend_Gdata_AuthSub::getHttpClient($_SESSION['sessionToken']);
			return $client;
		}
		
		
		function beforeFilter() {
            parent::beforeFilter();
			Zend_Loader::loadClass('Zend_Gdata');
            Zend_Loader::loadClass('Zend_Gdata_Docs');
			Zend_Loader::loadClass('Zend_Gdata_Calendar');
            Zend_Loader::loadClass('Zend_Gdata_AuthSub');
        }
        
        function google_doc() {
            $this->set('title_for_layout', 'Zend Gdata Docs Test');
            $this->set("array", array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V",
                                       "W","X","Y","Z"));
        }   
		
		function google_cal() {
			$eventFeed = "";
			$this->set('title_for_layout', "Zend Gdata Calendar Test");
			
			//AUTHENTICATE USER
			$client = $this->__getAuthSubHttpClient();
			
			//NEW CALENDAR SERVICE
			$service = new Zend_Gdata_Calendar($client);
			
			//GET LIST OF CALENDARS
			$listFeed = $service->getCalendarListFeed();
			
			//GET LIST OF EVENTS
			$index = 0;
			foreach($listFeed as $list) {
				$query = $service->newEventQuery($list->link[0]->href);
				// Set different query parameters
				$query->setUser('default');
				$query->setVisibility('private');
				$query->setProjection('full');
				$query->setOrderby('starttime');
				 
				// Get the event list
				try {
					$eventFeed[$index] = $service->getCalendarEventFeed($query);
				} catch (Zend_Gdata_App_Exception $e) {
					echo "Error: " . $e->getResponse() . "<br />";
				}
				$index++;
			}
			
			$this->set("list", $listFeed);
			$this->set("event", $eventFeed);
		}
		function live_feed() {
			$this->set('title_for_layout', "Live Feed");
			$this->loadModel("Thread");
		}
    }
?>