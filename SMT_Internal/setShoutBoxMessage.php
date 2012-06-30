<?php	
	include_once "../Utilities/AUTH.php";

	$handle = @fopen("../UsersDB/LastAjax/" . $_SESSION['SMT_UId'], "w");
		@fwrite($handle, time());
	@fclose($handle);

	/* Appends a message from current sender to today's ShoutBox */
	$todaysBox = "ShoutBox/AllBoxes/" . date("D_d-m-Y") . ".box";
	$todaysBoxHandle = @fopen($todaysBox, "a");
	if(!$todaysBoxHandle)
		die("Cannot open ShoutBox !!");
	
	$RealText = str_replace("\n", " ", $_POST['Shout']);
	
	$NormalSmileyList = array("bug", "BUG", ";)", ";-)", ":)", ":-|", ":|", ":'(", ":(", ":-)", ":-(", "B-)", "B)", "8)", "8-)", ":D", ":-D", ":d", ":-d", "LOL", "lol", "rofl", "ROFL", ":-O", ":-o", ":O", ":o", "o_o", "O_O", "X-(", "x-(", "x(", "X(", "<3", ":P", ":-P", ":p", ":-p", ":*", ":-*", ":S", ":-S", ":s", ":-s");
	for($i = 0; $i < count($NormalSmileyList); ++$i)
		$RealText = str_replace($NormalSmileyList[$i], "<img src=\"images/smileys/" . urlencode($NormalSmileyList[$i]) . ".gif\"/>", $RealText);

	$EscapedSmileyList = array("\m/", ":-/", ":/", ":\\", ":-\\", "..|. .|..");
	for($i = 0; $i < count($EscapedSmileyList); ++$i)
		$RealText = str_replace($EscapedSmileyList[$i], "<img src=\"images/smileys/escaped/" . urlencode(urlencode($EscapedSmileyList[$i])) . ".gif\"/>", $RealText);

	if(isset($_SESSION['SMT_MID']))
		@fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> <span class='chatGroup'>{" . $_SESSION['SMT_MID'] . "}</span> <span class='chatTime'>[" . date("h:i A") . "]</span> : " . $RealText . "<br>\n");
	else
		@fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> <span class='chatTime'>[" . date("h:i A") . "]</span> : " . $RealText . "<br>\n");

	@fclose($todaysBoxHandle);
?>