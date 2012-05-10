<h4>Google Calendar Zend Gdata Test Application</h4>
<br /><br />
List of Calendars:<br /><br />
<?php
	$index = 0;
	foreach ($list as $calendar) {
		echo "<a href='?id=".$calendar->id."'>" . $calendar->title . "</a> <br />";
		echo "<ul>";
		foreach ($event[$index] as $calEvents) {
			echo "<li>" . $calEvents->title . "</li>";
		}
		echo "</ul>";
		$index++;
	}
?>