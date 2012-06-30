<?php
	include_once "../Utilities/AUTH.php";

	$my_value = $_SESSION['SMT_UId'];
	$handle = @fopen("../UsersDB/LastAjax/" . $_SESSION['SMT_UId'], "w");
		@fwrite($handle, time());
	@fclose($handle);

	function filterFunc ($element) {
		global $my_value;
		return ($element != $my_value);
	}	

	$retArr = array();
	if(!file_exists("Matches/Waiting/" . $_POST['matchID'] . ".Wsmt")) {
		if(file_exists("Matches/Locked/" . $_POST['matchID'] . ".Lsmt")) {
			$retArr[] = false;
			$retArr[] = "The match " . $_POST['matchID'] . " is already locked and is in progress !";
		}
		else {
			$retArr[] = false;
			$retArr[] = "The match-ID " . $_POST['matchID'] . " is INVALID !!";
		}
	}
	else {
		if(isset($_SESSION['SMT_MID'])) {
			$matchFileHandler = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "r");
				$oldPlayers = @explode("\n", @trim(@fread($matchFileHandler, filesize("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt"))));
			@fclose($matchFileHandler);
			
			$oldPlayers = array_values(array_filter($oldPlayers, "filterFunc")); 
			$matchFileHandler = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "w");
				@fwrite($matchFileHandler, @implode("\n", $oldPlayers) . "\n");
			@fclose($matchFileHandler);

			$matchFileHandler = @fopen("Matches/Waiting/" . $_POST['matchID'] . ".Wsmt", "a");
				@fwrite($matchFileHandler, $_SESSION['SMT_UId'] . "\n");
			@fclose($matchFileHandler);
		}
		else {
			$matchFileHandler = @fopen("Matches/Waiting/" . $_POST['matchID'] . ".Wsmt", "a");
				@fwrite($matchFileHandler, $_SESSION['SMT_UId'] . "\n");
			@fclose($matchFileHandler);
		}
		
		$_SESSION['SMT_MID'] = $_POST['matchID'];
		$_SESSION['SMT_Role'] = "Passive";		
		
		$todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
		$todaysBoxHandle = @fopen($todaysBox, "a");
			@fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> joined match @ <span class='chatTime'>[" . date("h:i A") . "]</span><br>\n");
		@fclose($todaysBoxHandle);

		$retArr[] = true;
		$retArr[] = "You were added to match : " . $_POST['matchID'];
	}
	
	echo json_encode($retArr);
?>