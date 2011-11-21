<?php
    $this->Paginator->options(array(
        'update' => '#data',
        'evalScripts' => true,
        'before' => $this->Js->get('#load')->effect('fadeOut'),
        'complete' => $this->Js->get('#load')->effect('fadeIn')
    ));
?>
<?php 
    if(!($data == NULL)):
?>
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
    <table id="load">
        <tr>
            <th>Thread Name</th>
            <th>Created</th>
            <th>Last Post</th>
            <th>Replies</th>
            <th>Views</th>
        </tr>
        <?php
            $count = 0;
            foreach ($data as $row): 
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
            endforeach;
        ?>
    </table>
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
<?php
    else:
?>
    <table>
        <tr>
            <th colspan="5"><div align="center">No search results found.</div></th>
        </tr>
    </table>
<?php
    endif;
    echo $this->Js->writeBuffer(array('cache' => true));
?>