<?php 
    $html->css('profile_style', 'stylesheet', array('inline' => false));
    $html->addCrumb("My Forum Posts"); 
    $html->script('jquery.cookie.js', array('inline' => false));
    $html->script('jquery.idTabs.js', array('inline' => false));
    $threadsIpost = $this->Paging->paging($threadsIpost, 10);
?>
<div id="tabSelect">
    <ul> 
        <li><a href="#mythreads" id="mythread">My Threads</a></li> 
        <li><a href="#threadpost" id="threadIpost">Threads I've Posted To</a></li> 
    </ul> 
</div>
<div id="mythreads" style="display:none;">
    <?php
        if (($this->Paginator->hasNext() || $this->Paginator->hasPrev())):
    ?>
    <div class="paginator">
        <?php echo $this->Paginator->first("<< First ", array('class' => 'disabled')); ?>
        <?php echo $this->Paginator->prev("< Previous ", null, " ", array('class' => 'disabled')); ?>
        <div style="margin:5px;padding:5px;display:inline;"><?php echo $this->Paginator->numbers(array('modulus' => 5, 'seperator' => '|')); ?></div>
        <?php echo $this->Paginator->next(" Next >", null, " ", array('class' => 'disabled')); ?>
        <?php echo $this->Paginator->last(" Last >>", array('class' => 'disabled')); ?>
    </div>
    <?php
        endif;
    ?>
    <table>
        <tr>
            <th>Thread Name</th>
            <th>Created</th>
            <th>Last Post</th>
            <th>Replies</th>
            <th>Views</th>
        </tr>
        <?php
        if ($thread == NULL):
        ?>
            <tr>
                <td colspan="5"><h4><b><div align="center">You have not created any threads.</div></b></h4></td>
            </tr>
        <?php
        else:
            $count = 0;
            foreach ($thread as $row): 
                if($row['Thread']['private'] && !$sadmin):
                    continue;
                endif;
        ?> 
        <tr>
            <td title="<?php echo "".substr(strip_tags($row['Thread']['content']),0,100)."..."; ?>">
                <h4>
                <?php
                    if ($row['Thread']['sticky']) {
                        echo "Sticky: ";
                    }
                ?>
                    <a name="thread<?php echo $row['Thread']['id']; ?>"></a>
                    <?php echo $html->link($row['Thread']['thread_name'], "/posts/view/".$row['Thread']['id']."/");?>
                </h4> 
                <h1>by <b><?php echo $row['Thread']['username']; ?></b> </h1>
                <h3>
                <?php
                    echo "<h1>+<b>" .$row['thumbUp'] . "</b></h1>";
                ?>
                </h3>
            </td>
            <td><div class="modified" align="center"> 	
            <?php 
                echo $time->timeAgoInWords($row['Thread']['created'], array('format' => 'D M d, Y g:i a')); 
            ?> 
            </div></td>
            <td>
            <div class="modified" align="right">
                <?php 
                    if($row['Post'] != NULL):   
                ?>
                    <h4>&nbsp;&nbsp;&nbsp;
                    <i>
                        <?php 
                            $updated = $time->niceShort($row['Post']['modified']);
                            $updated = explode(',', $updated);
                            $updated[1] = $time->format('g:i a', $updated[1]);
                            echo "". $updated[0]. ", ".$updated[1];
                        ?> 
                        <br />
                    </i>
                    </h4>
                    by <b><?php echo $row['Post']['username']; ?></b>
                    &nbsp;
                    <?php 
                        //GRAB THE ID FROM LAST POST MADE TO POSTS DATABASE
                        $numPosts = $row['Thread']['posts'];
                        if($numPosts > 10) {
                            //Figure page
                            $page = floor($numPosts/10) + 1;
                        }
                        else {
                            $page = 1;
                        }
                        echo $html->link('View Now', "/posts/view/".$row['Post']['thread_id']."/page:".$page."#post".$row['Post']['id'].""); 
                    ?>
                    <br />
                <?php
                    else:
                ?>
                    <h4>&nbsp;&nbsp;&nbsp;
                    <i>
                        <?php 
                            $updated = $time->niceShort($row['Thread']['modified']);
                            $updated = explode(',', $updated);
                            $updated[1] = $time->format('g:i a', $updated[1]);
                            echo "". $updated[0]. ", ".$updated[1];
                        ?> 
                        <br />
                    </i>
                    </h4>
                    by <b><?php echo $row['Thread']['username']; ?></b>
                    &nbsp;
                    <?php 
                        echo $html->link('View Now', "/posts/view/".$row['Thread']['id']."/"); 
                    ?>
                    <br />

                <?php
                    endif;
                ?>

            </div></td>
            <td><div class="modified" align="center"> <?php echo $row['Thread']['posts']; ?> </div></td>
            <td><div class="modified" align="center"> <?php echo $row['Thread']['view']; ?> </div></td>
        </tr>
        <?php            
            $count++;
            endforeach;
        if($count < 1) {
        ?>
            <tr>
            <td colspan="5"><h4><b><div align="center">You have not created any threads.</div></b></h4></td>
            </tr>   
        <?php
            }
        endif;
        ?>
    </table>
    <?php
        if (($this->Paginator->hasNext() || $this->Paginator->hasPrev())):
    ?>
    <div class="paginator" >
        <?php echo $this->Paginator->first("<< First ", array('class' => 'disabled')); ?>
        <?php echo $this->Paginator->prev("< Previous ", null, " ", array('class' => 'disabled')); ?>
        <div style="margin:5px;padding:5px;display:inline;"><?php echo $this->Paginator->numbers(array('modulus' => 5, 'seperator' => '|')); ?></div>
        <?php echo $this->Paginator->next(" Next >", null, " ", array('class' => 'disabled')); ?>
        <?php echo $this->Paginator->last(" Last >>", array('class' => 'disabled')); ?>
         
    </div>
    <?php
        endif;
    ?>
</div>
<div id="threadpost" style="display:block;">
 <?php
        if (($this->Paging->hasNext() || $this->Paging->hasPrev())):
    ?>
    <div class="paginator">
    <?php
        echo $this->Paging->first("<< First");
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->back("< Previous");
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->pagedLinks(2);
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->next("Next >");
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->last("Last >>");
    ?>
    </div>
    <?php
        endif;
    ?>
    <table>
        <tr>
            <th>Thread Name</th>
            <th>Created</th>
            <th>Last Post</th>
            <th>Replies</th>
            <th>Views</th>
        </tr>
        <?php
        if ($thread == NULL):
        ?>
            <tr>
                <td colspan="5"><h4><b><div align="center">You have not created any threads.</div></b></h4></td>
            </tr>
        <?php
        else:
            $count = 0;
            foreach ($threadsIpost as $row): 
                if($row['Thread']['private'] && !$sadmin):
                    continue;
                endif;
        ?> 
        <tr>
            <td title="<?php echo "".substr(strip_tags($row['Thread']['content']),0,100)."..."; ?>">
                <h4>
                <?php
                    if ($row['Thread']['sticky']) {
                        echo "Sticky: ";
                    }
                ?>
                    <a name="thread<?php echo $row['Thread']['id']; ?>"></a>
                    <?php echo $html->link($row['Thread']['thread_name'], "/posts/view/".$row['Thread']['id']."/");?>
                </h4> 
                <h1>by <b><?php echo $row['Thread']['username']; ?></b> </h1>
                <h3>
                <?php
                    echo "<h1>+<b>" .$row['thumbUp'] . "</b></h1>";
                ?>
                </h3>
            </td>
            <td><div class="modified" align="center"> 	
            <?php 
                echo $time->timeAgoInWords($row['Thread']['created'], array('format' => 'D M d, Y g:i a')); 
            ?> 
            </div></td>
            <td>
            <div class="modified" align="right">
                <?php 
                    if($row['Post'] != NULL):  
                ?>
                    <h4>&nbsp;&nbsp;&nbsp;
                    <i>
                        <?php 
                            $updated = $time->niceShort($row['Post']['modified']);
                            $updated = explode(',', $updated);
                            $updated[1] = $time->format('g:i a', $updated[1]);
                            echo "". $updated[0]. ", ".$updated[1];
                        ?> 
                        <br />
                    </i>
                    </h4>
                    by <b><?php echo $row['Post']['username']; ?></b>
                    &nbsp;
                    <?php 
                        //GRAB THE ID FROM LAST POST MADE TO POSTS DATABASE
                        $numPosts = $row['Thread']['posts'];
                        if($numPosts > 10) {
                            //Figure page
                            $page = floor($numPosts/10) + 1;
                        }
                        else {
                            $page = 1;
                        }
                        echo $html->link('View Now', "/posts/view/".$row['Post']['thread_id']."/page:".$page."#post".$row['Post']['id'].""); 
                    ?>
                    <br />
                <?php
                    else:
                ?>
                    <h4>&nbsp;&nbsp;&nbsp;
                    <i>
                        <?php 
                            $updated = $time->niceShort($row['Thread']['modified']);
                            $updated = explode(',', $updated);
                            $updated[1] = $time->format('g:i a', $updated[1]);
                            echo "". $updated[0]. ", ".$updated[1];
                        ?> 
                        <br />
                    </i>
                    </h4>
                    by <b><?php echo $row['Thread']['username']; ?></b>
                    &nbsp;
                    <?php 
                        echo $html->link('View Now', "/posts/view/".$row['Thread']['id']."/"); 
                    ?>
                    <br />
                <?php
                    endif;
                ?>

            </div></td>
            <td><div class="modified" align="center"> <?php echo $row['Thread']['posts']; ?> </div></td>
            <td><div class="modified" align="center"> <?php echo $row['Thread']['view']; ?> </div></td>
        </tr>
        <?php            
            $count++;
            endforeach;
            if($count < 1):
        ?>
                <tr>
                <td colspan="5"><h4><b><div align="center">You have not created any threads.</div></b></h4></td>
                </tr>   
        <?php
            endif;
        endif;
        ?>
    </table>
    <?php
        if (($this->Paging->hasNext() || $this->Paging->hasPrev())):
    ?>
    <div class="paginator" >
    <?php
        echo $this->Paging->first("<< First");
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->back("< Previous");
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->pagedLinks(2);
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->next("Next >");
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $this->Paging->last("Last >>");
    ?>
    </div>
    <?php
        endif;
    ?>
</div>
<script type="text/javascript"> 
    $("#tabSelect ul").idTabs("mythreads");
</script>