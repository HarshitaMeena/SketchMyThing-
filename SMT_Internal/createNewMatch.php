<?php
	include_once "../Utilities/AUTH.php";
	
	$my_value = $_SESSION['SMT_UId'];
	$matchID = uniqid($prefix = "SMT_");
	while(file_exists("Matches/Waiting/$matchID") || file_exists("Matches/Locked/$matchID"))
		$matchID = uniqid($prefix = "SMT_");
	
	function filterFunc ($element) {
		global $my_value;
		return ($element != $my_value);
	}
	
	if(isset($_SESSION['SMT_MID'])) {
		$matchFileHandler = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "r");
			$oldPlayers = @explode("\n", @trim(@fread($matchFileHandler, filesize("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt"))));
		@fclose($matchFileHandler);
		
		$oldPlayers = array_values(array_filter($oldPlayers, "filterFunc")); 
		$matchFileHandler = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "w");
			@fwrite($matchFileHandler, @implode("\n", $oldPlayers) . "\n");
		@fclose($matchFileHandler);

		$matchFileHandler = @fopen("Matches/Waiting/$matchID.Wsmt", "a");
			@fwrite($matchFileHandler, $_SESSION['SMT_UId'] . "\n");
		@fclose($matchFileHandler);
	}
	else {
		$matchFileHandler = @fopen("Matches/Waiting/$matchID.Wsmt", "a");
			@fwrite($matchFileHandler, $_SESSION['SMT_UId'] . "\n");
		@fclose($matchFileHandler);
	}
	
	$_SESSION['SMT_MID'] = $matchID;
	$_SESSION['SMT_Role'] = "Active";

	$matchFileHandler = @fopen("Matches/MatchInfo/Hosts/$matchID.host", "w");
		@fwrite($matchFileHandler, $_SESSION['SMT_UId']);
	@fclose($matchFileHandler);
	
	$todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
	$todaysBoxHandle = @fopen($todaysBox, "a");
		@fwrite($todaysBoxHandle, "<span class='chatGroup'>{" . $_SESSION['SMT_MID'] . "}</span> match created @ <span class='chatTime'>[" . date("h:i A") . "]</span> by <span class='chatSuper'>" . $_SESSION['SMT_UName'] . "</span>.<br>\n");
	@fclose($todaysBoxHandle);

	$todaysBox = "ShoutBox/AllBoxes/" . date("D_d-m-Y") . ".box";
	$todaysBoxHandle = @fopen($todaysBox, "a");
	if($todaysBoxHandle) {
		@fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> has created a match <span class='chatGroup'>{" . $_SESSION['SMT_MID'] . "}</span> at <span class='chatTime'>[" . date("h:i A") . "]</span>.<br>\n");
		@fclose($todaysBoxHandle);
	}
?>