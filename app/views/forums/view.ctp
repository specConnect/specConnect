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
		</td>
		<td>
			<div class="modified">
				by <b><?php echo $row['Forum']['lastpost']; ?></b>
				&nbsp;
				<?php 
					//GRAB THE ID FROM LAST POST MADE TO POSTS DATABASE
					echo $html->link('View Now', "/posts/view?id=1"); 
				?>
				<br />
				<h4>&nbsp;&nbsp;&nbsp;
					<i>
						<?php 
							echo $time->timeAgoInWords($row['Forum']['modified']); 
						?> 
						<br />
					</i>
				</h4>
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