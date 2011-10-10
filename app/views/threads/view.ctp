<?php $html->addCrumb($title); ?>
<div align="right"><?php echo $html->link("+ New Thread","/threads/add/".$forum['Forum']['id']."/"); ?></div>
<br /><br />
<?php echo $this->Paginator->prev("<< Previous ", null, " ", array('class' => 'disabled')); ?>
<?php echo $this->Paginator->numbers(); ?>
<?php echo $this->Paginator->next(" Next >>", null, " ", array('class' => 'disabled')); ?>
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
		<td colspan="5"><h4><b><div align="center">NO POSTS IN THIS FORUM</div></b></h4></td>
	</tr>
	<?php
	else:
		foreach ($thread as $row): 
	?> 
	<tr>
		<td>
			<h4>
			<?php
				if ($row['Thread']['sticky']) {
					echo "Sticky: ";
				}
			?>
				<?php echo $html->link($row['Thread']['thread_name'], "/posts/view/".$row['Thread']['id']."/");?>
			</h4> 
			<h1>Pages: 1, 2, 3, ..., Last</h1>
			<h1>by <b><?php echo $row['Thread']['username']; ?></b> </h1>
            <?php 
            if($loggedUser == $row['Thread']['username'] || $admin) {
                echo $html->link('Delete', "delete/".$row['Thread']['id']."/".$row['Thread']['forum_id']."/"); 
            }
            ?>
		</td>
		<td><div class="modified" align="center"> 	
		<?php 
			echo $time->timeAgoInWords($row['Thread']['created']); 
		?> 
		</div></td>
		<td>
		<div class="modified" align="center">
			by <b><?php echo "someUser"; ?></b>
			&nbsp;
			<?php 
				//GRAB THE ID FROM LAST POST MADE TO POSTS DATABASE
				echo $html->link('View Now', "/posts/view?id=1"); 
			?>
			<br />
			<h4>&nbsp;&nbsp;&nbsp;
				<i>
					<?php 
							echo $time->timeAgoInWords($row['Thread']['modified']); 
					?> 
					<br />
				</i>
			</h4>
		</div></td>
		<td><div class="modified" align="center"> <?php echo $row['Thread']['posts'] ?> </div></td>
		<td><div class="modified" align="center"> 0 </div></td>
	</tr>
	<?php 
		endforeach;
	endif;
	?>
</table>