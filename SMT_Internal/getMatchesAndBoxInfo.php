<?php
	include_once "../Utilities/AUTH.php";
	
	$handle = @fopen("../UsersDB/LastAjax/" . $_SESSION['SMT_UId'], "w");
		@fwrite($handle, time());
	@fclose($handle);
	
	$maxReadLines = 25;
	$todaysBox = "ShoutBox/AllBoxes/" . date("D_d-m-Y") . ".box";
	$todaysBoxHandle = @fopen($todaysBox, "r");
	if(!$todaysBoxHandle)
		$retArray2 = array("POS" => 0, "DATA" => "");
	else {
		$data = "";
		$readLines = 0;
		$fromPos = $_POST['shoutFrom'];
		$maxReadLines += $fromPos;
		while ((!feof($todaysBoxHandle)) && ($readLines < $maxReadLines)) {
	   	$buffer = fgets($todaysBoxHandle);
	   	if($buffer !== false)
	   	{
	   		++$readLines;
	   		if($readLines > $fromPos)
	   			$data = $data . $buffer;
	   	}
	 	}
		@fclose($todaysBoxHandle);
		$retArray2 = array("POS" => $readLines, "DATA" => $data);
	}

	if(isset($_SESSION['SMT_MID'])) {
		$maxReadLines = 25;
		$todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
		$todaysBoxHandle = @fopen($todaysBox, "r");
		if(!$todaysBoxHandle) {
			$retArray2["MPOS"] = 0;
			$retArray2["MDATA"] = "";
		}
		else {
			$data = "";
			$readLines = 0;
			$fromPos = $_POST['matchShoutFrom'];
			$maxReadLines += $fromPos;
			while ((!feof($todaysBoxHandle)) && ($readLines < $maxReadLines)) {
		   	$buffer = fgets($todaysBoxHandle);
		   	if($buffer !== false)
		   	{
		   		++$readLines;
		   		if($readLines > $fromPos)
		   			$data = $data . $buffer;
		   	}
		 	}
			@fclose($todaysBoxHandle);
			$retArray2["MPOS"] = $readLines;
			$retArray2["MDATA"] = $data;
		}
	}
	
	if(!isset($_POST['NoPlayers'])) {
		if(!isset($_POST['onlyMyMatch'])) {
			$retArray = array();
			$retArray[] = array();
			$retArray[] = array();
			
			foreach (glob("Matches/Waiting/*.Wsmt") as $filename) {
				$fhandle = @fopen($filename, "r");
		      	$data = @implode(", ", @explode("\n", @trim(@fread($fhandle, filesize($filename)))));
		      @fclose($fhandle);
		      
				$retArray[0][] = array("TEAM" => basename($filename, ".Wsmt"), "PLAYERS" => $data); 
			}
			foreach (glob("Matches/Locked/*.Lsmt") as $filename) {
				$fhandle = @fopen($filename, "r");
		      	$data = @implode(", ", @explode("\n", @trim(@fread($fhandle, filesize($filename)))));
		      @fclose($fhandle);
		      
				$retArray[1][] = array("TEAM" => basename($filename, ".Lsmt"), "PLAYERS" => $data); 
			}
	
			echo @json_encode(array("MATCH" => $retArray, "SHOUT" => $retArray2));
		} else {
			if(file_exists("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt")) {
				$fhandle = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "r");
			   	$data = @explode("\n", @trim(@fread($fhandle, filesize("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt"))));
			   @fclose($fhandle); 
			    
			   $matchFileHandler = @fopen("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "r");
					$matchHost = @trim(@fread($matchFileHandler, filesize("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host")));
				@fclose($matchFileHandler);
				
				if($matchHost == $_SESSION['SMT_UId'] && $_SESSION['SMT_Role'] != 'Active')
					echo @json_encode(array("PLAYERS" => $data, "SHOUT" => $retArray2, "UNSTABLE" => true));
				else if($matchHost != $_SESSION['SMT_UId'] && $_SESSION['SMT_Role'] == 'Active')
					echo @json_encode(array("PLAYERS" => $data, "SHOUT" => $retArray2, "UNSTABLE" => true));
				else
					echo @json_encode(array("PLAYERS" => $data, "SHOUT" => $retArray2, "UNSTABLE" => false));
			} elseif(file_exists("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt")) {
				$_SESSION['SMT_Busy'] = true;
	
				$fhandle = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "r");
			   	$data = @explode("\n", @trim(@fread($fhandle, filesize("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt"))));
			   @fclose($fhandle); 
	
				$fhandle = @fopen("../UsersDB/MatchesPlayed/" . $_SESSION['SMT_UId'] . ".game", "a");
			   	@fwrite($fhandle, $_SESSION['SMT_MID'] . " : {" . implode(",", $data) . "}\n");
			   @fclose($fhandle); 			
				
				echo @json_encode(array("PLAYERS" => $data, "SHOUT" => $retArray2, "UNSTABLE" => true));
			}
		}
	} else {
		$matchFileHandler = @fopen("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time", "r");
			$matchTime = intval(@trim(@fread($matchFileHandler, filesize("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time"))));
		@fclose($matchFileHandler);
		$stabilityTimeLeft = (60 - (time() - $matchTime));
		
		$matchFileHandler = @fopen("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "r");
			$matchHost = @trim(@fread($matchFileHandler, filesize("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host")));
		@fclose($matchFileHandler);

		if($stabilityTimeLeft < 1) {
			$handle = @fopen("Dicts/Pictionary.txt", "r");
				$data = @explode("\n", @fread($handle, filesize("Dicts/Pictionary.txt")));
			@fclose($handle);
			$matchWord = $data[rand(0, count($data))];
	
			$handle = @fopen("Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word", "w");
				@fwrite($handle, $matchWord);
			@fclose($handle);

			$handle = @fopen("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time", "w");
				@fwrite($handle, time()+2);
			@fclose($handle);

			$fhandle = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "r");
			   $data = @explode("\n", @trim(@fread($fhandle, filesize("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt"))));
			@fclose($fhandle);
			
			$oldHost = array_shift($data);
			$data[] = $oldHost;
			
			$fhandle = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "w");
				@fwrite($fhandle, @implode("\n", $data) . "\n");
			@fclose($fhandle);
			
			$fhandle = @fopen("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "w");
				@fwrite($fhandle, $data[0]);
			@fclose($fhandle);

			echo @json_encode(array("SHOUT" => $retArray2, "TIMELEFT" => $stabilityTimeLeft, "UNSTABLE" => true));
		}
		else {
			if($matchHost == $_SESSION['SMT_UId'] && $_SESSION['SMT_Role'] != 'Active')
				echo @json_encode(array("SHOUT" => $retArray2, "UNSTABLE" => true, "TIMELEFT" => $stabilityTimeLeft));
			else if($matchHost != $_SESSION['SMT_UId'] && $_SESSION['SMT_Role'] == 'Active')
				echo @json_encode(array("SHOUT" => $retArray2, "UNSTABLE" => true, "TIMELEFT" => $stabilityTimeLeft));
			else
				echo @json_encode(array("SHOUT" => $retArray2, "UNSTABLE" => false, "TIMELEFT" => $stabilityTimeLeft));
		}
	}
?>