<?php
    //document to connect to the database, required in all the documents that do server interactions
	$mysqli = new mysqli('localhost', 'wustl_inst', 'wustl_pass', 'cal');

	if($mysqli->connect_errno) {
		printf("Connection Failed: %s\n", $mysqli->connect_error);
		exit;
	}
?>