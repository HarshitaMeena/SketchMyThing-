<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/reqAuth.php";

    /* Appends a message from current sender to today's ShoutBox */
    $todaysBox = "ShoutBox/AllBoxes/" . date("D_d-m-Y") . ".box";
    $todaysBoxHandle = @fopen($todaysBox, "a");
    if(!$todaysBoxHandle)
        die("Cannot open ShoutBox !!");

    $RealText = str_replace("\n", " ", $_POST['Shout']);

    $NormalSmileyList = array("bug", ";)", ";-)", ":)", ":-|", ":|", ":'(", ":(", ":-)", ":-(", "b-)", "b)", "8)", "8-)", ":d", ":-d", "lol", "rofl", ":-o", ":o", "o_o", "x-(", "x(", "<3", ":p", ":-p", ":*", ":-*", ":s", ":-s");
    for($i = 0; $i < count($NormalSmileyList); ++$i)
        $RealText = str_ireplace($NormalSmileyList[$i], "<img src=\"images/smileys/" . urlencode($NormalSmileyList[$i]) . ".gif\"/>", $RealText);

    $EscapedSmileyList = array("\m/", ":-/", ":/", ":\\", ":-\\", "..|. .|..");
    for($i = 0; $i < count($EscapedSmileyList); ++$i)
        $RealText = str_ireplace($EscapedSmileyList[$i], "<img src=\"images/smileys/escaped/" . urlencode(urlencode($EscapedSmileyList[$i])) . ".gif\"/>", $RealText);

    if(isset($_SESSION['SMT_MID']))
        @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> <span class='chatGroup'>{" . $_SESSION['SMT_MID'] . "}</span> <span class='chatTime'>[" . date("h:i A") . "]</span> : " . $RealText . "<br>\n");
    else
        @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> <span class='chatTime'>[" . date("h:i A") . "]</span> : " . $RealText . "<br>\n");

    @fclose($todaysBoxHandle);
?>
