<?php 
    class PagingHelper extends AppHelper {
        var $helpers = array('Html');
        var $__current;
        var $__maxPage;

        function __getPageFromURL($url) {
            $pageNum = null;
            $url = explode("page:", $url);
            $url = str_split($url[1]);
            for($i = 0; $i < count($url); $i++) {
                if(is_numeric($url[$i])) {
                    $pageNum .= $url[$i];
                }
                else {
                    break;
                }
            }
            if($pageNum == null) {
                $pageNum = 1;
            }
            return $pageNum;
        }
        
        function __getMaxPage($count, $limit) {
            $q = floor($count/$limit);
            $r = $count%$limit;
            if($r == 0) {
                return $q;
            }
            else if($r < $limit) {
                return $q+1;
            }
            else {
                $pages = 0;
                while($r >= 0) {
                    $r -= $limit;
                    $pages++;
                }
                return $q+$pages;
            }
        }
        
        function hasNext() {
            if($this->__current < $this->__maxPage) {
                return true;
            }
            return false;
        }
        
        function hasPrev() {
            if(($this->__current - 1) > 0) {
                return true;
            }
            return false;
        }
        
        function paging($array, $limit) {
            $pageData = NULL;
            $count = count($array);
            $this->__maxPage = $this->__getMaxPage($count, $limit);
            $url = $_SERVER['QUERY_STRING'];
            if(strstr($url,"page:") != FALSE) {
                $num = $this->__getPageFromURL($url);
                if($num <= 0) {
                    $num = 1;
                }
                if($num > $this->__maxPage) {
                    $num = $this->__maxPage;
                }
                $this->__current = $num;
                $displayed = ($this->__current - 1) * $limit;
                $count -= $displayed;
                if($limit > $count) {
                    $limit = $count;
                }
                $index = 0;
                for($i = 0; $i < $limit; $i++) {
                    $pageData[$index] = $array[$displayed + $i];
                    $index++;
                }
            }
            else {
                $this->__current = 1;
                $index = 0;
                if($limit > $count) {
                    $limit = $count;
                }
                for($i = 0; $i < $limit; $i++) {
                    $pageData[$index] = $array[$i];
                    $index++;
                }
            }
            return $pageData;
        }
        
        function next($title) {
            $next = $this->__current + 1;
            if($this->__maxPage >= $next) {
                echo $this->Html->link($title, array('page' => "$next"));
            }
        }
        
        function back($title) {
            $back = $this->__current - 1;
            if($back > 0) {
                echo $this->Html->link($title, array('page' => "$back"));
            }
        }
        
        function last($title) {
            $next = $this->__current + 1;
            if($this->__maxPage >= $next) {
                echo $this->Html->link($title, array('page' => $this->__maxPage));
            }
        }
        
        function first($title) {
            $back = $this->__current - 1;
            if($back > 0) {
                echo $this->Html->link($title, array('page' => "1"));
            }
        }
        
        function pagedLinks($surround) {
            if($this->__maxPage > 1) {
                for($j = $surround; $j > 0; $j--) {
                    $title = $this->__current - $j;
                    if($title > 0) {
                        echo $this->Html->link($title, array('page' => $title));
                        echo "&nbsp; | &nbsp;";
                    }
                }
                if($this->__current != 0) {
                    echo "<b>".$this->__current."</b>";
                }
                for($j = 1; $j <= $surround; $j++) {
                    $title = $this->__current + $j;
                    if($title <= $this->__maxPage) {
                        echo "&nbsp | &nbsp;";
                        echo $this->Html->link($title, array('page' => $title));
                    }
                }
            }
        }
    }
?>