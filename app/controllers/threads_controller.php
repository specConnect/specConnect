<?php 
	class ThreadsController extends AppController {
		var $paginate; 
        var $name = 'Threads';
		var $helpers = array('Form', 'Html', 'Js', 'Time', 'Paging', 'Paginator');
        
        function __getPage($posts) {
            if($posts > 10) {
                return (floor($posts/10)) + 1;
            }
            else {
                return 1;
            }
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
        
        function search() {
            $this->layout = "forum";
            $this->set('title_for_layout', "specConnect - Search");
            $this ->Session->write("search", $this->data['Thread']['search']);
            if($this->RequestHandler->isAjax()) {
                $sterm = NULL;
                if(empty($this->data)) {
                    if(!($this->Session->read("search") == NULL)) {
                        $sterm = $this->Session->read("search");
                    }
                }
                else {
                    $sterm = $this->data['Thread']['search'];
                }
                //Set pagination parameters
                $this->paginate = array(
                            'Thread' => array(
                                'limit' => 1, 
                                'conditions' => array('thread_name LIKE' => "%".$sterm."%"),
                                'recursive' => 1, 
                                'order' => array('sticky DESC','modified DESC')
                            )
                        );
                $thread = $this->paginate('Thread');
                $index = 0;
                foreach ($thread as $row) {
                    $post = $this->Thread->Post->find('first', array('conditions' => array('thread_id' => $row['Thread']['id']), 'order' => array('modified DESC')));
                    $thread[$index]['Post'] = $post['Post'];
                    $thread[$index]['thumbUp'] = count($thread[$index]['Thumb']);
                    $thread[$index]['Thread']['view'] = count($thread[$index]['ThreadView']);
                    $x = 0;
                    $thread[$index]['voted'] = 0;
                    foreach ($thread[$index]['Thumb'] as $thumbs) {
                        if($thread[$index]['Thumb'][$x]['username'] == $this->Auth->user('username')) {
                            $thread[$index]['voted'] = 1;
                            break;
                        }
                        $x++;
                    }
                    $index++;
                }
                $this->set('data', $thread);
                $this->render("data", "ajax");
            }
        }
        
        function myposts() {
            $this->set('loggedUser', $this->Auth->user('username'));
            $this->set('admin', $this->__isAdmin());
            $this->set('sadmin', $this->__isSuperAdmin());
            
            //Load up Forum Model so that we can get some information about the forum
            $this->loadModel('Forum');
            $this->loadModel('User');
            $this->loadModel('Post');
            $this->loadModel('Thumb');
            

                        //Set pagination parameters
            $this->paginate = array(
                        'Thread' => array(
                            'limit' => 10, 
                            'conditions' => array('username' => $this->Auth->user('username')),
                            'recursive' => 1, 
                            'order' => array('sticky DESC','modified DESC')
                        )
                    );
                    
            $this->layout = 'forum';
            
            $this->set('title_for_layout', 'specConnect - Threads');
            
            $thread = $this->paginate('Thread');
                
            $index = 0;
            foreach ($thread as $row) {
                $post = $this->Post->find('first', array('conditions' => array('thread_id' => $row['Thread']['id']), 'order' => array('modified DESC')));
                $thread[$index]['Post'] = $post['Post'];
                $thread[$index]['thumbUp'] = count($thread[$index]['Thumb']);
                $thread[$index]['Thread']['view'] = count($thread[$index]['ThreadView']);
                $x = 0;
                $thread[$index]['voted'] = 0;
                foreach ($thread[$index]['Thumb'] as $thumbs) {
                    if($thread[$index]['Thumb'][$x]['username'] == $this->Auth->user('username')) {
                        $thread[$index]['voted'] = 1;
                        break;
                    }
                    $x++;
                }
                $index++;
            }
            
            $threads = $this->Thread->find('all',array('conditions' => array('username <>' => $this->Auth->user('username')),'order' => array('modified DESC')));
            $threadsIpost = null;
            $index = 0;
            foreach ($threads as $row) {
                foreach($row['Post'] as $col) {
                    if($col['username'] == $this->Auth->user('username')) {
                        $threadsIpost[$index] = $row;
                        $post = $this->Post->find('first', array('conditions' => array('thread_id' => $row['Thread']['id']), 'order' => array('modified DESC')));
                        $threadsIpost[$index]['Post'] = $post['Post'];
                        $threadsIpost[$index]['thumbUp'] = count($threadsIpost[$index]['Thumb']);
                        $threadsIpost[$index]['Thread']['view'] = count($threadsIpost[$index]['ThreadView']);
                        $index++;
                        break;
                    }
                }
            }
           
            //Title above Breadcrumb
            $this->set('sadmin', $this->__isSuperAdmin());
            $this->set('thread', $thread);
            $this->set('threadsIpost', $threadsIpost);
        }
        
        function thumb($id = NULL, $f_id = NULL) {
            $this->autoRender = false;
            if($id != NULL && $f_id != NULL) {
                $this->loadModel('Forum');
                $forum = $this->Forum->find('first', array('conditions' => array('id' => $f_id), 'recursive' => 0));
                $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                if($forum != NULL && $thread != NULL) {
                    if($thread['Thread']['private'] && $this->Auth->user('roles') != 'sadmin') {
                        $this->redirect('/forums/view/');
                    }
                    $thumb = $this->Thread->Thumb->find('first', array('conditions' => array('thread_id' => $thread['Thread']['id'], 
                    'username' => $this->Auth->user('username'))));
                    if(!$thumb) { 
                        $val = array('username' => $this->Auth->user('username'),
                                     'user_id' => $this->Auth->user('id'),
                                     'thread_id' => $id,
                                     'ip' => $this->RequestHandler->getClientIp());
                        $this->Thread->Thumb->create();
                        $this->Thread->Thumb->save($val);
                        $this->Session->setFlash("Thumbs up to: " . $thread['Thread']['thread_name']);
                        $page = $this->__getPage($forum['Forum']['threads']);
                        $this->redirect("/threads/view/$f_id/page:$page#thread$id");
                    }
                    else {
                        $this->Session->setFlash("Thumbs down to: " . $thread['Thread']['thread_name']."");
                        $this->Thread->Thumb->query("DELETE FROM `thumbs` WHERE `thread_id` = $id AND `username` = '".$this->Auth->user('username')."'");
                        $page = $this->__getPage($forum['Forum']['threads']);
                        $this->redirect("/threads/view/$f_id/page:$page#thread$id");
                    }
                }
                else {
                    $this->redirect('/forums/view');
                }
            }
            else {
                $this->redirect('/forums/view');
            }
        }
        
        function edit($id = NULL) {
            $this->layout = 'forum';
            $user = $this->Auth->user('username');
            $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
            if($thread == NULL) {
                $this->redirect('/forums/view/');
            }
            else {
                if($thread['Thread']['private'] && $this->Auth->user('roles') != 'sadmin') {
                    $this->redirect('/forums/view/');
                }
                $this->set('sadmin', $this->__isSuperAdmin());
                $this->set('private', $thread['Thread']['private']);
            }
            if($user == $thread['Thread']['username'] || $this->__isAdmin()) {
                if($id != NULL) {
                    $this->set('title_for_layout', 'specConnect - Edit Thread');
                    $this->loadModel('Forum');
                    $forum = $this->Forum->find('first', array('conditions' => array('id' => $thread['Thread']['forum_id']), 
                    'fields' => array('name'),'recursive' => 0));
                    $this->set('title', array(0 => $forum['Forum']['name'], 'link0' => "/threads/view/".$thread['Thread']['forum_id']."", 1 => "Edit Thread")); 
                    
                    if(!empty($this->data)) { //IF USER POSTS DATA
                        //Save to database
                        $this->data['id'] = $id;
                        $this->set('id', $thread['Thread']['id']);
                        $this->set('thread_name', NULL);
                        $this->set('content', NULL);
                        if($this->Thread->save($this->data)) {
                            $this->Session->setFlash("Editing successful.");
                            $this->redirect("/posts/view/".$thread['Thread']['id']."/");
                        }
                        else {
                            $this->Session->setFlash("Unable to update post. Try again.");
                        }
                    }
                    else { //WHEN NEW DATA ISN'T POSTED
                        $this->set('id', $thread['Thread']['id']);
                        $this->set('thread_name', $thread['Thread']['thread_name']);
                        $this->set('content', $thread['Thread']['content']);
                    }
                }
                else {
                    $this->redirect(array('controller' => 'forums', 'action' => 'view'));
                }
            }                
            else {
                $this->Session->setFlash('You cannot edit a thread which does not belong to you.');
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
        
        function delete($id = NULL, $f_id = NULL) {
            $this->autoRender = false;
            $user = $this->Auth->user('username');
            $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
            if($thread == NULL) {
                $this->redirect('/forums/view/');
            }
            else {
                if($thread['Thread']['private'] && $this->Auth->user('roles') != 'sadmin') {
                    $this->redirect('/forums/view/');
                }
            }
            if($user == $thread['Thread']['username'] || $this->__isAdmin()) {
                if($id != NULL) {
                    $this->loadModel('Post');
                    $this->loadModel('Forum');
                    $this->loadModel('User');
                    
                    //Find posting user
                    $user = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0, 'fields' => array('username')));
                    
                    //Delete all Threads, and Posts attached to Threads
                    $this->Thread->delete($id, false);
                    $this->Post->deleteAll(array('thread_id' => $id));
                    $this->Thread->Subscription->deleteAll(array('thread_id' => $id));
                    $this->Thread->Thumb->deleteAll(array('thread_id' => $id));
                    
                    $forum = $this->Forum->find('first', array('conditions' => array('id' => $f_id), 'recursive' => 0));
                    if($forum['Forum']['threads'] > 0) {
                        $forum['Forum']['threads'] = $forum['Forum']['threads'] - 1;
                    }
                    if($forum['Forum']['posts'] > 0) {
                        $forum['Forum']['posts'] = $forum['Forum']['posts'] - $thread['Thread']['posts'];
                    }
                    
                    $this->Forum->save(array('id' => $f_id, 'threads' => $forum['Forum']['threads'], 'posts' => $forum['Forum']['posts']),
                    array('validate' => false, 'fieldList' => array('threads', 'posts')));
                    
                    $this->Session->setFlash('Thread deleted successfully.');
                    $page = $this->__getPage($forum['Forum']['threads']);
                    $this->redirect("/threads/view/$f_id/page:$page"); 
                }
                else {
                    $this->redirect(array('controller' => 'forums', 'action' => 'view'));
                }
            }
            else {
                $this->Session->setFlash('You cannot delete a thread which does not belong to you.');
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
        
		function add($id = NULL) {
				$this->layout = 'forum';
				$this->set('title_for_layout', 'specConnect - Add Thread');
                
                $this->set('sadmin', $this->__isSuperAdmin());
                
                $this->loadModel('Forum');

                if($id != NULL) {
                    $forums = $this->Forum->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                    if($forums == NULL) {
                        $this->redirect('/forums/view/');
                    }
                    $this->set('title', array(0 => $forums['Forum']['name'], 'link0' => "/threads/view/$id", 1 => "Add New Thread"));

                }
                
				if(!empty($this->data)) {
                    //Adding a thread
					$this->data['Thread']['username'] = $this->Auth->user('username');
                    $this->data['Thread']['forum_id'] = $id;
                    $this->Thread->create(); //Gets ready to create new entry in database
					if($this->Thread->save($this->data)) {
                    
                        //Subscribe to thread user posts in
                        $forumSub = $this->Forum->ForumSubscription->find('first', array('conditions' => array('forum_id' => $id, 
                        'username' => $this->Auth->user('username'))));
                        if($forumSub == NULL) {
                            $this->Thread->Subscription->create();
                            $this->Thread->Subscription->save(array('thread_id' => $this->Thread->id, 'username' => $this->Auth->user('username'),
                            'email' => $this->Auth->user('email'), 'first_name' => $this->Auth->user('first_name')));
                        }
                        
                        $this->loadModel('User');
                        //Update number for threads in forum and last posting user
                        $threads = $this->Forum->find('first', array('conditions' => array('id' => $id), 'fields' => array('threads'), 'recursive' => 0));
                        $threads = $threads['Forum']['threads'];
                        $threads = $threads + 1;
                        $this->Forum->save(array('id' => $id, 'threads' => $threads), array('validate' => false,
                        'fieldList' => array('threads')));
                        
                        //User just added a thread
                        $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'recursive' => 0));
                        $user['User']['posts'] = $user['User']['posts'] + 1;
                        $this->User->query("UPDATE `users` SET  `posts` = '".$user['User']['posts']."' WHERE `id` = ".$user['User']['id'].";");
                        
                        //Save user rating
                        $this->__getSaveRating($this->Auth->user('id'), $user['User']['posts']);
                        
                        $forumSub = $this->Forum->ForumSubscription->find('all', array('conditions' => array('forum_id' => $id)));
                        $userCache = $this->User->find('all', array('fields' => array('username', 'roles')));
                        if($forumSub != NULL) {
                            foreach ($forumSub as $row) {
                                if($row['ForumSubscription']['username'] != $this->Auth->user('username')) {
                                    $user = $this->__find($userCache, 'User', 'username', $row['ForumSubscription']['username']);
                                    if($this->data['Thread']['private'] && $user['User']['roles'] != 'sadmin') {
                                        //DO NOTHING
                                    }
                                    else {
                                        $this->Email->reset();
                                        //$this->Email->delivery = "debug";
                                        $this->Email->delivery = "mail";
                                        $this->Email->from = 'specConnect@spec.net';
                                        $this->Email->to = $row['ForumSubscription']['email'];
                                        $this->Email->subject = $this->Auth->user('username') . " just posted on " . $this->data['Thread']['thread_name'] ;
                                        $this->Email->sendAs = 'html';
                                        $this->Email->layout = 'default';
                                        $this->Email->template = 'subscription_message';
                                        $modified = date('Y-m-d G:i:s');
                                        $content = "" . $this->data['Thread']['content'] . "*(*)*" . $this->Auth->user('username') . "*(*)*" . $this->data['Thread']['thread_name'] . "*(*)*" 
                                                   . $row['ForumSubscription']['first_name'] . "*(*)*" . $modified  . "*(*)*" . "/posts/view/".$this->Thread->id."";
                                        $this->Email->send($content);
                                    }
                                }
                            }
                        }
                        $this->Session->setFlash("Thread added successfully");
                        $this->redirect(array('controller' => 'threads', 'action' => "view/".$id."#thread".$this->Thread->id.""));
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
                                'recursive' => 1, 
                                'order' => array('sticky DESC','modified DESC')
                            )
                        );
                        
				$this->layout = 'forum';
				
				$this->set('title_for_layout', 'specConnect - Threads');
                
				//Load up Forum Model so that we can get some information about the forum
				$this->loadModel('Forum');
				$this->loadModel('User');
                $this->loadModel('Post');
                $this->loadModel('Thumb');
                
				$forum = $this->Forum->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                
                if($forum != NULL) {
                    $thread = $this->paginate('Thread');
                        
                    $index = 0;
                    foreach ($thread as $row) {
                        $post = $this->Post->find('first', array('conditions' => array('thread_id' => $row['Thread']['id']), 'order' => array('modified DESC')));
                        $thread[$index]['Post'] = $post['Post'];
                        $thread[$index]['thumbUp'] = count($thread[$index]['Thumb']);
                        $thread[$index]['Thread']['view'] = count($thread[$index]['ThreadView']);
                        $x = 0;
                        $thread[$index]['voted'] = 0;
                        foreach ($thread[$index]['Thumb'] as $thumbs) {
                            if($thread[$index]['Thumb'][$x]['username'] == $this->Auth->user('username')) {
                                $thread[$index]['voted'] = 1;
                                break;
                            }
                            $x++;
                        }
                        $thread[$index]['sub'] = 0;
                        if($this->Auth->user()) {
                            $forumSub = $this->Forum->ForumSubscription->find('first', array('conditions' => array('forum_id' => $id, 
                            'username' => $this->Auth->user('username'))));
                            if($forumSub == NULL) {
                                $x = 0;
                                foreach ($thread[$index]['Subscription'] as $subs) {
                                    if($thread[$index]['Subscription'][$x]['username'] == $this->Auth->user('username')
                                       && $thread[$index]['Subscription'][$x]['thread_id'] == $thread[$index]['Thread']['id']) {
                                        $thread[$index]['sub'] = 1;
                                        break;
                                    }
                                    $x++;
                                }
                            }
                            else {
                                $thread[$index]['sub'] = 3;
                            }
                        }
                       
                        $index++;
                    }
                    
                    //Title above Breadcrumb
                    $this->set('title', $forum['Forum']['name']);
                    $this->set('sadmin', $this->__isSuperAdmin());
                    $this->set('forum', $forum);
                    $this->set('thread', $thread);
                }
                else {
                    $this->redirect("/forums/view/");
                }
            }
		}
        
        function sticky($id = NULL, $f_id = NULL) {
            $this->autoRender = false;
            if($this->__isSuperAdmin() || $this->__isAdmin()) {
                if($id != NULL && $f_id != NULL) {
                    $sticky = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                    $sticky = $sticky['Thread']['sticky'];
                    $sticky = $sticky ? 0 : 1;
                    if($this->Thread->save(array('id' => $id, 'sticky' => $sticky), array('validate' => false,
                        'fieldList' => array('sticky')))) {
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