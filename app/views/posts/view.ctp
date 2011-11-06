<?php 
    $html->addCrumb($title[0], $title['link0']);
    $html->addCrumb($title[1]); 
?>
<?php
    echo "<br />";
    if($online && ($sub == 1)) {
        echo $html->link('Subscribe', "/posts/subscribe/".$thread['Thread']['id']."/");
    }
    else if($online && ($sub == 0)) {
        echo $html->link('Unsubscribe', "/posts/subscribe/".$thread['Thread']['id']."/");
    }
    else {
    
    }
?>
<div class="paginator">
    <?php echo $this->Paginator->first("<< First ", array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->prev("< Previous ", null, " ", array('class' => 'disabled')); ?>
    <div style="margin:5px;padding:5px;display:inline;"><?php echo $this->Paginator->numbers(array('modulus' => 5, 'seperator' => '|')); ?></div>
    <?php echo $this->Paginator->next(" Next >", null, " ", array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->last(" Last >>", array('class' => 'disabled')); ?>
</div>
<div align="left" class="postReply">
<?php echo $html->link("+Reply", "/posts/add/".$thread['Thread']['id']."/"); ?>
</div>
<table>
<?php 
    if ($this->Paginator->current() == 1 || $this->Paginator->current() == 0): 
?>
        <tr>
            <th><?php echo $time->timeAgoInWords($thread['Thread']['created']); ?></th>
            <th>
                <div align="left">
                    <?php echo $title[1]; ?>
                </div>
            </th>
        </tr>
        <tr>
            <td style="border-right: 1px solid #bbb;">
                <div align="center">
                    <?php echo $html->image($thread_user['User']['avatar']); ?>
                    <br /><br />
                    <div  style="padding:5px;" class="postUser">
                        <b><?php echo $thread_user['User']['username']; ?></b> <br />
                        <div style="padding-left:20px;"><i><?php echo $thread_user['User']['email']; ?></i></div><br />
                        <b><?php echo $thread_user['Profile']['university']; ?> </b> <br />
                        <div style="padding-left:20px;"><i><?php echo $thread_user['Profile']['university_program']; ?></i></div>
                        <?php
                            if ($thread_user['Profile']['job'] != NULL && $thread_user['Profile']['job_title'] != NULL):
                        ?>
                                <br />
                                <b><?php echo $thread_user['Profile']['job']; ?> </b> <br />
                                <div style="padding-left:20px;"><i><?php echo $thread_user['Profile']['job_title']; ?></i></div>
                        <?php 
                            endif;
                        ?>
                    </div> 
                    <div  style="padding:5px;" class="postUser">
                    Posts: <i><?php echo $thread_user['User']['posts']; ?></i><br />
                    <b><?php echo $thread_user['Profile']['rating']; ?></b></div>
                    <br /><br />
                </div>
            </td>
            <td width="70%">
                <div class="post">
                    <?php echo $thread['Thread']['content']; ?>
                    <br />
                    <hr />
                    <?php echo $thread_user['Profile']['signature']; ?>
                    <div align="right">
                    <?php
                        echo "<br /><br />";
                        if($loggedUser == $thread['Thread']['username'] || $admin):
                            echo $html->link('Delete Thread', "/threads/delete/".$thread['Thread']['id']."/".$thread['Thread']['forum_id']."/",
                            array('onclick' => "return deletechecked();"));
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            echo $html->link('Edit', "/threads/edit/".$thread['Thread']['id']."/");
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        endif;
                        echo $html->link('+Quote', "/posts/add/".$thread['Thread']['id']."/".$thread['Thread']['id']."/");
                    ?>
                    </div>
                </div>
            </td>
        </tr>
<?php 
    endif; 
?>
    <?php 
        foreach ($posts as $row): 
    ?>
    <tr>
        <th>
            <?php echo $time->timeAgoInWords($row['Post']['modified']); ?>
        </th>
        <th><div align="left"><?php echo "RE: " .$title[1]; ?></div></th>
    </tr>
    <tr>
        <td style="border-right: 1px solid #bbb;">
            <div align="center">
                <?php echo $html->image($row['User']['avatar']); ?> <br /><br />
                <div  style="padding:5px;" class="postUser" align="left">
                    <b><?php echo $row['User']['username']; ?></b> <br />
                    <div style="padding-left:20px;"><i><?php echo $row['User']['email']; ?></i></div><br />
                    <b><?php echo $row['Profile']['university']; ?> </b> <br />
                    <div style="padding-left:20px;"><i><?php echo $row['Profile']['university_program']; ?></i></div>
                    <?php
                        if ($row['Profile']['job'] != NULL && $row['Profile']['job_title'] != NULL):
                    ?>
                            <br />
                            <b><?php echo $row['Profile']['job']; ?> </b> <br />
                            <div style="padding-left:20px;"><i><?php echo $row['Profile']['job_title']; ?></i></div>
                    <?php 
                        endif;
                    ?>
                </div>
               <div  style="padding:5px;" class="postUser" align="left">
               Posts: <i><?php echo $row['User']['posts']; ?></i><br />
               <b><?php echo $row['Profile']['rating']; ?></b>
               </div>
                <br /><br />
                <a name="<?php echo "post".$row['Post']['id']; ?>"></a>
            </div>
        </td>
        <td width="70%">
            <div class="post">
                <?php echo $row['Post']['content']; ?>
                <br />
                <hr />
                <?php echo $row['Profile']['signature']; ?>
                <div align="right">
                <?php 
                    echo "<br /><br />";
                    if($loggedUser == $thread['Thread']['username'] || $admin):
                        echo $html->link('Delete Post', "delete/".$row['Post']['id']."/".$row['Post']['thread_id']."/",
                        array('onclick' => "return deletechecked();"));                        
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo $html->link('Edit', "/posts/edit/".$row['Post']['id']."/");
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    endif;
                    echo $html->link('+Quote', "/posts/add/".$row['Post']['thread_id']."/".$row['Post']['id']."/");
                ?>
                </div>
            </div>
        </td>
    </tr>
    <?php
        endforeach; 
        //echo $this->Session->flash('email');
    ?>
</table>
<div align="left" class="postReply">
<?php echo $html->link("+Reply", "/posts/add/".$thread['Thread']['id']."/"); ?>
</div>
<div class="paginator" >
    <?php echo $this->Paginator->first("<< First ", array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->prev("< Previous ", null, " ", array('class' => 'disabled')); ?>
    <div style="margin:5px;padding:5px;display:inline;"><?php echo $this->Paginator->numbers(array('modulus' => 5, 'seperator' => '|')); ?></div>
    <?php echo $this->Paginator->next(" Next >", null, " ", array('class' => 'disabled')); ?>
    <?php echo $this->Paginator->last(" Last >>", array('class' => 'disabled')); ?>
</div>