<?php
	
	$fh = fopen("php_errors.log", 'r');

    $pageText = fread($fh, 25000);

    echo nl2br($pageText);

?>