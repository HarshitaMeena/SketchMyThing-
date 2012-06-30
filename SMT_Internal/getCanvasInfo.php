<?php
	include_once "../Utilities/AUTH.php";

	$handle = @fopen("../UsersDB/LastAjax/" . $_SESSION['SMT_UId'], "w");
		@fwrite($handle, time());
	@fclose($handle);
	
	$mousefile = "Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev"; 
	$handle = @fopen($mousefile, "r");
	$data = @fread($handle, filesize($mousefile));
	@fclose($handle);
	
	if(strlen($data) < $_POST['FROM'])
		$rdata = $data;
	else
		$rdata = substr($data, $_POST['FROM']);
		
	if($rdata === false)
		$rdata = "";

	echo json_encode(array("DATA" => $rdata, "POS" => strlen($data)));
?>