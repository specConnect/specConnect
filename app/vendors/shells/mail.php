<?php
class MailShell extends Shell {
	var $uses = array('Thread');
	function main() {
		$list = $this->Thread->find("all");
		foreach ($list as $entry) {
			$this->out($entry['Thread']['username']. " posted " .$entry['Thread']['thread_name'] . " on " .
			$entry['Thread']['created'] . "\n");
		}
	}
}
?>