<?php $html->addCrumb($title); ?>
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
<div align="right" class="postReply">
    <?php echo $html->link("+Thread","/threads/add/".$forum['Forum']['id']."/"); ?>
</div>
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
            <td colspan="5"><h4><b><div align="center">NO THREADS IN THIS FORUM</div></b></h4></td>
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
                <?php
                    if  ($online && ($row['sub'] == 0)) {
                        echo "&nbsp; &nbsp;";
                        echo $html->link('Subscribe', "/posts/subscribe/".$row['Thread']['id']."/");
                    }
                    else if($online && ($row['sub'] == 1)){
                        echo "&nbsp; &nbsp;";
                        echo $html->link('Unsubscribe', "/posts/subscribe/".$row['Thread']['id']."/");
                    }
                    else {

                    }
                ?>
			</h4> 
			<h1>by <b><?php echo $row['Thread']['username']; ?></b> </h1>
            <h3>
            <?php 
            if($loggedUser == $row['Thread']['username'] || $admin) :
                echo $html->link('Delete', "delete/".$row['Thread']['id']."/".$row['Thread']['forum_id']."/",
                array('onclick' => "return deletechecked();"));
            ?>
                &nbsp; &nbsp;
            <?php
                echo $html->link('Edit', "edit/".$row['Thread']['id']."/");
            ?>
                &nbsp; &nbsp;
            <?php
                if ($admin) {
                    echo $html->link('Toggle Sticky', "sticky/".$row['Thread']['id']."/".$row['Thread']['forum_id']."/");
                }
            endif;
            if  ($online && !($row['voted'])) {
                echo "&nbsp; &nbsp;";
                echo $html->link('+1', "/threads/thumb/".$row['Thread']['id']."/".$row['Thread']['forum_id']."/");
            }
            else if($online){
                echo "&nbsp; &nbsp;";
                echo $html->link('-1', "/threads/thumb/".$row['Thread']['id']."/".$row['Thread']['forum_id']."/");
            }
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
        <td colspan="5"><h4><b><div align="center">NO THREADS IN THIS FORUM</div></b></h4></td>
        </tr>   
    <?php
        }
	endif;
    //echo $this->Session->flash('email');
	?>
</table>
<div align="right" class="postReply">
    <?php echo $html->link("+Thread","/threads/add/".$forum['Forum']['id']."/"); ?>
</div>
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