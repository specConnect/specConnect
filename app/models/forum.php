<?php 
	class Forum extends AppModel {
		var $name = "Forum";
		var $hasMany = array(
			'Thread' => array(
				'className' => 'Thread',
				'order' => array('sticky DESC','modified DESC')
			),
            'ForumSubscription' => array(
				'className' => 'ForumSubscription',
			)
		);
	}
?>