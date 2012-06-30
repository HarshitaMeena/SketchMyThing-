<?php
	include_once "../Utilities/AUTH.php";

	$handle = @fopen("../UsersDB/LastAjax/" . $_SESSION['SMT_UId'], "w");
		@fwrite($handle, time());
	@fclose($handle);

	if(isset($_POST['Start'])) {
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
		
		@copy("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt");
		@unlink("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt");
		
		$todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
		$todaysBoxHandle = @fopen($todaysBox, "a");
			@fwrite($todaysBoxHandle, "<span class='chatGroup'>{" . $_SESSION['SMT_MID'] . "}</span> match started @ <span class='chatTime'>[" . date("h:i A") . "]</span> ...<br>\n");
		@fclose($todaysBoxHandle);
	}
?>