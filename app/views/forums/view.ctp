<table>
	<tr>
		<th>Forum Name</th>
		<th>Last Post</th>
		<th>Threads</th>
		<th>Posts</th>
	</tr>
	<?php
		$last = "";
		foreach ($forum as $row) : 
	?>
	<?php if($last != $row['Forum']['category']): ?>
	<tr>
		<th colspan="4">
			<div align="left">
				<?php echo $row['Forum']['category']; ?>
			</div>
		</th>
	</tr>
	<?php endif; ?>
	<tr>
		<td>
			<h4><?php echo $html->link("".$row['Forum']['name']."", "/threads/view/".$row['Forum']['id']."/"); ?></h4>
			<h1><?php echo $row['Forum']['summary']; ?></h1>
            <h3>
                <?php
                    if($online && !$row['sub']) {
                        echo $html->link('Subscribe to Forum', "/forums/subscribe/".$row['Forum']['id']."");
                    }
                    else if($online){
                        echo $html->link('Unsubscribe from Forum', "/forums/subscribe/".$row['Forum']['id']."");
                    }
                ?>
            </h3>
		</td>
		<td>
			<div class="modified" align="right">
				<?php 
					//GRAB THE ID FROM LAST POST MADE TO POSTS DATABASE
                    if(($row['Forum']['threads'] == 0) || (($row['Thread'] == NULL) && $row['Post'] == NULL)):
                        echo "<b>No threads in this forum</b><br />";
                        echo $html->link('Add a Thread Now', "/threads/add/".$row['Forum']['id']."/"); 
                    elseif($row['Thread'] == NULL):
                        $numPosts = $row['Post']['posts'];
                        if($numPosts > 10) {
                            $page = floor($numPosts/10) + 1;
                        }
                        else {
                            $page = 1;
                        }
                        echo $html->link(substr(strip_tags($row['Post']['content']),0,50)."...", "/posts/view/".$row['Post']['thread_id']."/page:$page#post".$row['Post']['id']."", 
                        array('escape' => false));
                        echo "<br />";
                        echo "by <b>" . $row['Post']['username'] . "</b>&nbsp;";
                        echo $html->link('View Now', "/posts/view/".$row['Post']['thread_id']."/page:$page#post".$row['Post']['id']."");
                    ?>
                       <br />
                        <h4>&nbsp;&nbsp;&nbsp;
                            <i>
                                <?php 
                                    echo $time->timeAgoInWords($row['Post']['modified'], array('format' => 'D M d, Y g:i a')); 
                                ?> 
                                <br />
                            </i>
                        </h4>
                <?php
                    else:
                        echo $html->link(substr(strip_tags($row['Thread']['thread_name']),0,50)."...", "/posts/view/".$row['Thread']['id'], 
                        array('escape' => false));
                        echo "<br />";
                        echo "by <b>" . $row['Thread']['username'] . "</b>&nbsp;";
                        echo $html->link('View Now', "/posts/view/".$row['Thread']['id']."/"); 
                    ?>
                       <br />
                        <h4>&nbsp;&nbsp;&nbsp;
                            <i>
                                <?php 
                                    echo $time->timeAgoInWords($row['Thread']['modified'], array('format' => 'D M d, Y g:i a')); 
                                ?> 
                                <br />
                            </i>
                        </h4>
                <?php
                    endif;
				?>
			</div>
		</td>
		<td><div class="modified" align="center"><?php echo $row['Forum']['threads']; ?></td>
		<td><div class="modified" align="center"><?php echo $row['Forum']['posts'] ?></div></td>
	</tr>
	<?php
		$last = $row['Forum']['category'];	
		endforeach; 
	?>
</table>