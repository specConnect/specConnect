<?php $html->addCrumb($title); ?>
<div align="right"><?php echo $html->link('+Reply', "/posts/add/".$thread['Thread']['id']."/"); ?></div>
<?php
    echo "<br />";
    if($online && $sub) {
        echo $html->link('Subscribe', "/posts/subscribe/".$thread['Thread']['id']."/");
    }
    else if($online && !$sub) {
        echo $html->link('Unsubscribe', "/posts/subscribe/".$thread['Thread']['id']."/");
    }
?>
<br />
<?php echo $this->Paginator->prev("<< Previous ", null, " ", array('class' => 'disabled')); ?>
<div style="margin:5px;padding:5px;display:inline;"><?php echo $this->Paginator->numbers(array('modulus' => 5, 'seperator' => '|')); ?></div>
<?php echo $this->Paginator->next(" Next >>", null, " ", array('class' => 'disabled')); ?>
<table>
<?php 
    if ($this->Paginator->current() == 1 || $this->Paginator->current() == 0): 
?>
        <tr>
            <th><?php echo $time->timeAgoInWords($thread['Thread']['created']); ?></th>
            <th><div align="left"><?php echo $title; ?></div></th>
        </tr>
        <tr>
            <td style="border-right: 1px solid #bbb;">
                <div align="center">
                    <?php echo $html->image($thread_user['User']['avatar']); ?> <br /><br />
                    <div style="background:#AAA; border:1px solid #000; padding:5px;">
                        <?php echo $thread_user['User']['username']; ?> <br />
                        <?php echo $thread_user['User']['email']; ?>
                    </div> 
                    Posts: <i><?php echo $thread_user['User']['posts']; ?></i>
                    <br /><br />
                    <?php
                        if($loggedUser == $thread['Thread']['username'] || $admin):
                            echo $html->link('Delete Thread', "/threads/delete/".$thread['Thread']['id']."/".$thread['Thread']['forum_id']."/");
                            echo "<br />";
                            echo $html->link('Edit', "/threads/edit/".$thread['Thread']['id']."/");
                        endif;
                        echo "<br />";
                        echo $html->link('Quote', "/posts/add/".$thread['Thread']['id']."/".$thread['Thread']['id']."/");
                    ?>
                </div>
            </td>
            <td width="70%">
                <div class="post">
                    <?php echo $thread['Thread']['content']; ?>
                    <br />
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
        <th><div align="left"><?php echo "RE: " .$title; ?></div></th>
    </tr>
    <tr>
        <td style="border-right: 1px solid #bbb;">
            <div align="center">
                <?php echo $html->image($row['User']['avatar']); ?> <br /><br />
                <div style="background:#AAA; border:1px solid #000; padding:5px;">
                    <?php echo $row['User']['username']; ?> <br />
                    <?php echo $row['User']['email']; ?> 
                </div>
                Posts: <i><?php echo $row['User']['posts']; ?></i>
                <br /><br />
                <?php
                    if($loggedUser == $row['Post']['username'] || $admin):
                        echo $html->link('Delete Post', "delete/".$row['Post']['id']."/".$row['Post']['thread_id']."/");
                        echo "<br />";
                        echo $html->link('Edit', "edit/".$row['Post']['id']."/");
                    endif;
                    echo "<br />";
                    echo $html->link('Quote', "/posts/add/".$row['Post']['thread_id']."/".$row['Post']['id']."/");
                ?>
                <a name="<?php echo "post".$row['Post']['id']; ?>"></a>
            </div>
        </td>
        <td width="70%">
            <div class="post">
                <?php echo $row['Post']['content']; ?>
                <br />
            </div>
        </td>
    </tr>
    <?php
        endforeach; 
        //echo $this->Session->flash('email');
    ?>
</table>