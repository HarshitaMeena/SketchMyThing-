<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/reqAuth.php";

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
