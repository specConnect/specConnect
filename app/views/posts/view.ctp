<?php $html->addCrumb($title); ?>
<?php echo $html->link('+New Post', "/posts/add/".$thread['Thread']['id']."/"); ?>

<table>
    <tr>
        <th><?php echo $time->timeAgoInWords($thread['Thread']['created']); ?></th>
        <th><div align="right">#1</div></th>
    </tr>
    <tr>
        <td style="border-right: 1px solid #bbb;">
            <div align="center">
                <?php echo $html->image($thread_user['User']['avatar']); ?> <br /><br />
                <div style="background:#AAA; border:1px solid #000; padding:5px;">
                    <?php echo $thread_user['User']['username']; ?> <br />
                    <?php echo $thread_user['User']['email']; ?>
                </div>
            </div>
        </td>
        <td width="70%">
            <b><?php echo $title; ?></b>
            <hr /><br />
            <?php echo $thread['Thread']['content']; ?>
        </td>
    </tr>
    <?php 
    $index = 0;
    $count = 2;
        foreach ($posts as $row): 
    ?>
    <tr>
        <th>
            <?php echo $time->timeAgoInWords($row['modified']); ?>
            <a name="<?php echo "post".$row['id']; ?>"></a>
        </th>
        <th><div align="right">#<?php echo $count; ?></div></th>
    </tr>
    <tr>
        <td style="border-right: 1px solid #bbb;">
            <?php echo $row['Post']['User']['username']; ?> <br />
            <?php echo $row['Post']['User']['email']; ?>
        </td>
        <td width="70%">
            <b><?php echo "RE: " .$title; ?></b>
            <hr /><br />
            <?php echo $row['content']; ?>
        </td>
    </tr>
    <?php
        $index++;
        $count++;
        endforeach; 
    ?>
</table>