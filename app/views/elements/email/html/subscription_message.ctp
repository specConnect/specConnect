<p>
<?php 
    $content = explode('*(*)*', $content);
?>
    Dear <b><?php echo $content[3]; ?></b>, <br />
    <b><?php echo $content[1]; ?></b> has posted the following message in thread <b><?php echo $content[2]; ?></b>: 
    <br />
    <?php 
        echo $html->link('View on specConnect', "http://www.d1138213.domain.com/specConnect".$content[5]); 
    ?> 
    <br />
    <div style="border: 1px solid #000; padding:5px;">
        <?php echo $content[0]; ?>
    </div>
    <br />
    <i>
        <?php
            $updated = $content[4];
            $updated = explode(' ', $updated);
            $updated[1] = $time->format('g:i a', $updated[1]);
            echo "". $updated[0]. ", ".$updated[1];
        ?>
    </i>
</p>