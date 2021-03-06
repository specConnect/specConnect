<?php
    class PostsController extends AppController {
        var $paginate;
        var $name = 'Posts';
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
        
        function subscribe($id = NULL) {
            $this->autoRender = false;
            if($id != NULL) {
                $this->loadModel('Thread');
                $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'fields' =>  array('private'), 'recursive' => 0));
                if($thread['Thread']['private'] && $this->Auth->user('roles') != 'sadmin') {
                    $this->Session->setFlash('Could not proccess your request.');
                    $this->redirect('/forums/view/');
                }
                else {
                    $subscript = $this->Thread->Subscription->find('first', array('conditions' => array('thread_id' => $id, 
                    'username' => $this->Auth->user('username'))));
                    if($subscript == NULL ) {
                        $this->Thread->Subscription->create();
                        if($this->Thread->Subscription->save(array('thread_id' => $id, 'username' => $this->Auth->user('username'),
                        'email' => $this->Auth->user('email'), 'first_name' => $this->Auth->user('first_name')))) {
                            $this->Session->setFlash("Subscription successfull.");
                            $this->redirect("/posts/view/$id/");
                        }
                    }
                    else {
                        $this->Session->setFlash("Unsubscribed successfully.");
                        $this->Thread->Subscription->query("DELETE FROM `subscriptions` WHERE `thread_id` = $id AND `username` = '".$this->Auth->user('username')."'");
                        $this->redirect("/posts/view/$id/");
                    }
                }
            }
            else {
                $this->redirect("/forums/view/");
            }   
        }
        
        function edit($id = NULL) {
            $this->layout = 'forum';
            $user = $this->Auth->user('username');
            $post = $this->Post->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
            if($post == NULL) {
                $this->redirect('/forums/view/');
            }
            if($user == $post['Post']['username'] || $this->__isAdmin()) {
                if($id != NULL) {
                    $this->loadModel('Forum');
                    $this->loadModel('Thread');
                    $this->set('title_for_layout', 'specConnect - Edit Post');
                    $thread = NULL;
                    if(!empty($this->data)) { //IF USER POSTS DATA
                        //Save to database
                        $this->set('id', $post['Post']['id']);
                        $this->set('content', NULL);
                        if($this->Post->query("UPDATE `posts` SET  `content` = '".$this->data['Post']['content']."' WHERE  `id` = $id;")) {
                            $this->Session->setFlash("Editing successful.");
                            $thread = $this->Thread->find('first', array('conditions' => array('id' => $post['Post']['thread_id']), 'recursive' => 0));
                            $page = $this->__getPage($thread['Thread']['posts']);
                            $this->redirect("/posts/view/".$post['Post']['thread_id']."/page:$page#post".$post['Post']['id']."");
                        }
                        else {
                            $this->Session->setFlash("Unable to update post. Try again.");
                        }
                    }
                    else { //WHEN NEW DATA ISN'T POSTED
                        $this->set('id', $post['Post']['id']);
                        $this->set('content', $post['Post']['content']);
                    }
                    if($thread == NULL) {
                        $thread = $this->Thread->find('first', array('conditions' => array('id' => $post['Post']['thread_id']), 'fields' => array('id','forum_id','thread_name'), 'recursive' => 0));
                    }              
                    $forum = $this->Forum->find('first', array('conditions' => array('id' => $thread['Thread']['forum_id'])));
                    $this->set('title', array(0 => $forum['Forum']['name'], 'link0' => "/threads/view/".$forum['Forum']['id']."", 
                    1 => $thread['Thread']['thread_name'],'link1' => "/posts/view/".$thread['Thread']['id']."" , 2 => "Edit Post"));
                }
                else {
                    $this->redirect(array('controller' => 'forums', 'action' => 'view'));
                }
            }                
            else {
                $this->Session->setFlash('You cannot edit a post which does not belong to you.');
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
        
        function add($id = NULL, $q_id = NULL) {
            $this->layout = 'forum';
            $this->set('title_for_layout', 'specConnect - Reply');
            $this->loadModel('Thread');
            $this->loadModel('Forum');
            $this->loadModel('User');
            $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));          
            $forum = $this->Forum->find('first', array('conditions' => array('id' => $thread['Thread']['forum_id'])));
            $this->set('title', array(0 => $forum['Forum']['name'], 'link0' => "/threads/view/".$forum['Forum']['id']."", 
            1 => $thread['Thread']['thread_name'],'link1' => "/posts/view/".$thread['Thread']['id']."" , 2 => "Reply"));
            if($id != NULL) {
                $posts = $thread;
                if($posts == NULL) {
                    $this->redirect('/forums/view/');
                }
                if($q_id != NULL) {
                    if($q_id == $id) { 
                        //We are quoting a thread
                        $quote = $this->Thread->find('first', array('conditions' => array('id' => $q_id)));
                        $quote_id = $quote['Thread']['id'];
                        $quote = "Originally Posted by <b>".$quote['Thread']['username']."</b><br />".$quote['Thread']['content'];
                    }
                    else {
                        //We are quoting a post
                        $quote = $this->Post->find('first', array('conditions' => array('id' => $q_id)));
                        $quote_id = $quote['Post']['thread_id'];
                        $quote = "Originally Posted by <b>".$quote['Post']['username']."</b><br />".$quote['Post']['content'];
                    }
                    
                    if($quote_id == $posts['Thread']['id']) {
                        $this->set('quote', $quote);
                    }
                    else {
                        $this->Session->setFlash("Cannot handle your request");
                        $this->redirect('/forums/view/');
                    }
                }
            }
            
            if(!empty($this->data)) {

                //Adding a post
                $this->data['Post']['username'] = $this->Auth->user('username');
                $this->data['Post']['thread_id'] = $id;
                $this->Post->create(); //Gets ready to create new entry in database
                if($this->Post->save($this->data)) {
                    //Update number of posts in thread
                    $forum_id = $posts['Thread']['forum_id'];
                    $posts = $posts['Thread']['posts'];
                    $posts = $posts + 1;
                    $this->Thread->save(array('id' => $id, 'posts' => $posts));
                    
                    //Subscribe to thread user posts in
                    $f_sub = $this->Forum->ForumSubscription->find('first', array('conditions' => array('forum_id' => $forum_id,
                    'username' => $this->Auth->user('username'))));
                    if($f_sub == NULL) {
                        if($this->Thread->Subscription->find('first', array('conditions' => 
                        array('thread_id' => $id, 'username' => $this->Auth->user('username')))) == NULL) {
                            $this->Thread->Subscription->create();
                            $this->Thread->Subscription->save(array('thread_id' => $id, 'username' => $this->Auth->user('username'), 
                            'email' => $this->Auth->user('email'), 'first_name' => $this->Auth->user('first_name')));
                        }   
                    }
                    
                    $page = $this->__getPage($posts);
                    
                    //Update number for posts in forum
                    $posts = $this->Forum->find('first', array('conditions' => array('id' => $forum_id), 'fields' => array('posts'), 'recursive' => 0));
                    $posts = $posts['Forum']['posts'];
                    $posts = $posts + 1;
                    
                    //User just added a post
                    $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'recursive' => 0));
                    $user['User']['posts'] = $user['User']['posts'] + 1;
                    $this->User->query("UPDATE `users` SET  `posts` = '".$user['User']['posts']."' WHERE `id` = ".$user['User']['id'].";");
             
                    //Save user rating
                    $this->__getSaveRating($this->Auth->user('id'), $user['User']['posts']);
                    
                    $this->Forum->save(array('id' => $forum_id, 'posts' => $posts), array('validate' => false,
                        'fieldList' => array('posts')));
                    $this->Session->setFlash("Post added successfully");
                    $post_id = $this->Post->id;
                    $subscription = $this->Thread->Subscription->find('all', array('conditions' => array('thread_id' => $id)));
                    $forumSub = $this->Forum->ForumSubscription->find('all', array('conditions' => array('forum_id' => $forum_id)));
                    $userCache = $this->User->find('all', array('fields' => array('username', 'roles')));
                    //Send emails to users subscribed to post
                    if($subscription != NULL) {
                        foreach ($subscription as $row) {
                            if($row['Subscription']['username'] != $this->Auth->user('username')) {
                                $this->Email->reset();
                                //$this->Email->delivery = "debug";
                                $this->Email->delivery = "mail";
                                $this->Email->from = 'specConnect@spec.net';
                                $this->Email->to = $row['Subscription']['email'];
                                $this->Email->subject = $this->Auth->user('username') . " just posted on " . $thread['Thread']['thread_name'];
                                $this->Email->sendAs = 'html';
                                $this->Email->layout = 'default';
                                $this->Email->template = 'subscription_message';
                                $content = $this->data['Post']['content'] . "*(*)*" . $this->Auth->user('username') . "*(*)*" . $thread['Thread']['thread_name'] . "*(*)*" 
                                           . $row['Subscription']['first_name'] . "*(*)*" . $thread['Thread']['modified'] . "*(*)*" . "/posts/view/$id/page:$page#post$post_id";
                                $this->Email->send($content);
                            }
                        }
                    }
                    if($forumSub != NULL) {
                        foreach ($forumSub as $row) {
                            if($row['ForumSubscription']['username'] != $this->Auth->user('username')) {
                                $user = $this->__find($userCache, 'User', 'username', $row['ForumSubscription']['username']);
                                if($thread['Thread']['private'] && $user['User']['roles'] != 'sadmin') {
                                    //DO NOTHING
                                }
                                else {
                                    $this->Email->reset();
                                    //$this->Email->delivery = "debug";
                                    $this->Email->delivery = "mail";
                                    $this->Email->from = 'specConnect@spec.net';
                                    $this->Email->to = $row['ForumSubscription']['email'];
                                    $this->Email->subject = $this->Auth->user('username') . " just posted on " . $thread['Thread']['thread_name'];
                                    $this->Email->sendAs = 'html';
                                    $this->Email->layout = 'default';
                                    $this->Email->template = 'subscription_message';
                                    $content = $this->data['Post']['content'] . "*(*)*" . $this->Auth->user('username') . "*(*)*" . $thread['Thread']['thread_name'] . "*(*)*" 
                                               . $row['ForumSubscription']['first_name'] . "*(*)*" . $thread['Thread']['modified'] . "*(*)*" . "/posts/view/$id/page:$page#post$post_id";
                                    $this->Email->send($content);
                                }
                            }
                        }
                    }
                    
                    $this->redirect(array('controller' => 'posts', 'action' => "view/".$id."/page:".$page."#post$post_id"));
                }
                else {
                    $this->Session->setFlash("Post was not added. Error occured. Try again.");
                }
                
            }
            else if($id == NULL) {
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
            
            $this->set('thread_id', $id);
            $this->set('quote_id', $q_id);
		}
        
        function delete($id = NULL, $t_id = NULL) {
            $this->autoRender = false;
            $user = $this->Auth->user('username');
            $post = $this->Post->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
            if($user == $post['Post']['username'] || $this->__isAdmin()) {
                if($id != NULL) {
                    $this->loadModel('Thread');
                    $this->loadModel('Forum');
                    $this->loadModel('User');
                    
                    //Find posting user
                    $user = $this->Post->find('first', array('conditions' => array('id' => $id), 'recursive' => 0, 'fields' => array('username')));
                    
                    //Delete post
                    $this->Post->query("DELETE FROM `posts` WHERE `id` = $id");
                    
                    $thread = $this->Thread->find('first', array('conditions' => array('id' => $t_id), 'recursive' => 0));
                    if($thread['Thread']['posts'] > 0) {
                        $thread['Thread']['posts'] = $thread['Thread']['posts'] - 1;
                        $this->Thread->save(array('id' => $t_id, 'posts' => $thread['Thread']['posts']), array('validate' => false,
                        'fieldList' => array('posts')));
                    }
                    
                    $forum = $this->Forum->find('first', array('conditions' => array('id' => $thread['Thread']['forum_id']), 'recursive' => 0));

                    if($forum['Forum']['posts'] > 0) {
                        $forum['Forum']['posts'] = $forum['Forum']['posts'] - 1;
                        $this->Forum->save(array('id' => $forum['Forum']['id'], 'posts' => $forum['Forum']['posts']), array('validate' => false,
                        'fieldList' => array('posts')));
                    }
                    
                    $this->Session->setFlash('Post deleted successfully.');
                    $page = $this->__getPage($thread['Thread']['posts']);
                    $this->redirect("/posts/view/$t_id/page:$page"); 
                }
                else {
                    $this->redirect(array('controller' => 'forums', 'action' => 'view'));
                }
            }
            else {
                $this->Session->setFlash('You cannot delete a post which does not belong to you.');
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
        
        function view($id = NULL) {
                //Check if post exists
                if($id != NULL) {
                    $ip = $this->RequestHandler->getClientIp();
                    $this->loadModel('Forum');
                    $this->loadModel('Thread');
                    $thread = $this->Thread->find('first', array('conditions' => array('id' => $id), 'recursive' => 0));
                    $view = $this->Thread->ThreadView->find('first', array('conditions' => array('ip' => $ip, 'thread_id' => $id)));
                    if($view == NULL) {
                        $this->Thread->ThreadView->create();
                        $this->Thread->ThreadView->save(array('ip' => $ip, 'thread_id' => $thread['Thread']['id']));
                    }
                    if ($thread != NULL):
                        if($thread['Thread']['private'] && $this->Auth->user('roles') != 'sadmin') {
                            $this->redirect('/forums/view/');
                        }
                        //Set pagination parameters
                        $this->paginate = array(
                                    'Post' => array(
                                        'limit' => 10, 
                                        'conditions' => array('thread_id' => $id),
                                        'recursive' => 0, 
                                        'order' => array('modified ASC')
                                    )
                                );
                        
                        if($this->Auth->user()) {
                            $fsub = $this->Forum->ForumSubscription->find('first', array('conditions' => array('forum_id' => $thread['Thread']['forum_id'],
                            'username' => $this->Auth->user('username'))));
                            if($fsub == NULL) {
                                $sub = $this->Thread->Subscription->find('first', array('conditions' => array('thread_id' => $id, 
                                'username' => $this->Auth->user('username'))));
                                if($sub == NULL) {
                                    $this->set('sub', 1);
                                }
                                else {
                                    $this->set('sub', 0);
                                }
                            }
                            else {
                                $this->set('sub', 3);
                            }
                        }
                        
                        $this->layout = 'forum';
                        $this->set('title_for_layout', 'specConnect - Threads');
                        $this->loadModel('User');
                        $thread_user = $this->User->find('first', array('conditions' => array('username' => $thread['Thread']['username'])));
                        $posts = $this->paginate('Post');
                        $forum = $this->Forum->find('first', array('conditions' => array('id' => $thread['Thread']['forum_id']), 'recursive' => 0));
                        
                        $index = 0;
                        foreach ($posts as $row) {
                            $post_user = $this->User->find('first', array('conditions' => array('username' => $row['Post']['username']), 'recursive' => 0));
                            $posts[$index]['User'] = $post_user['User'];
                            $posts[$index]['Profile'] = $post_user['Profile'];
                            $index++;
                        }
                        
                        $this->set('admin', $this->__isAdmin());
                        $this->set('loggedUser', $this->Auth->user('username'));
                        $this->set('title', array(0 => $forum['Forum']['name'], 'link0' => "/threads/view/".$forum['Forum']['id']."", 
                        1 => $thread['Thread']['thread_name']));
                        $this->set('thread', $thread);
                        $this->set('thread_user', $thread_user);
                        $this->set('posts', $posts);
                    else: 
                        $this->redirect(array('controller' => 'forums', 'action' => "view"));
                    endif;
            }
            else {
                $this->redirect(array('controller' => 'forums', 'action' => 'view'));
            }
        }
    }
?>