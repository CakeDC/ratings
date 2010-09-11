<?php 
Configure::write('debug', 0);
$status = (isset($status)) ? $status : 'success'; 
?>{ "status" : "<?php echo $status?>", "data" : <?php echo $content_for_layout; ?> }