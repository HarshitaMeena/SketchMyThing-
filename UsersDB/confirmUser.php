<?php
	include_once "../Utilities/POST.php";
	
	$postData = array();
	
	if(!isset($_GET['code'])) {
		$postData['defStatus'] = false;
		$postData['defMsg'] = "No verification code to be confirmed !";
	}
	else {
		if($_GET['code'] == "") {
			$postData['defStatus'] = false;
			$postData['defMsg'] = "No verification code to be confirmed !";
		}
		else if(file_exists("PendingHashes/" . $_GET['code'])) {
			$handle = @fopen("PendingHashes/" . $_GET['code'], "r");
				$userName = @trim(@fread($handle, filesize("PendingHashes/" . $_GET['code'])));
			@fclose($handle);
			
			if(file_exists("ToBeVerified/$userName")) {
				$postData['defStatus'] = true;
				$postData['defMsg'] = "$userName [" . $_GET['code'] . "] was verified ! :-)";
				
				@unlink("PendingHashes/" . $_GET['code']);
				@copy("ToBeVerified/$userName", "Members/$userName");
				@unlink("ToBeVerified/$userName");
			}
			else {
				$postData['defStatus'] = false;
				$postData['defMsg'] = "Verification code [" . $_GET['code'] . "] has expired !";
			}
		}
		else {
			$postData['defStatus'] = false;
			$postData['defMsg'] = "Verification code [" . $_GET['code'] . "] has expired !";
		}
	}
	
	redirectWithPost($postData, "../");
?>