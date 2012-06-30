<?php
	$directory = "Members/";
	if (glob($directory . "*") != false)
		$filecount = count(glob($directory . "*"));
	else
		$filecount = 0;

	if($_GET['action'] == 'get_count')
		die("" . $filecount);
	
	header("Location: ../");
	die();
?>