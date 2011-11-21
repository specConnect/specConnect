<?php
    class FeedsController extends AppController {
        var $name = 'Feeds';
        var $uses = array('Thread', 'Post');
        var $helpers = array('Form', 'Html', 'Js', 'Time', 'Paging', 'Paginator');
        
        function getFeed() {
           //Get latest feeds from Facebook/Twitter/specConnect/GoogleCalendar
        }
    }
?>