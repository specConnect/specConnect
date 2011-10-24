<?php
    class FeedsController extends AppController {
        var $name = 'Feeds';
        var $uses = array('Thread', 'Post');
        
        function getFeed() {
           //Get latest feeds from Facebook/Twitter/specConnect/GoogleCalendar
        }
    }
?>