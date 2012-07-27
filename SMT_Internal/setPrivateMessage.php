<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/reqAuth.php";

    /* Obtain the word of the current game */
    $filep = "Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word";
    $fhandle = @fopen($filep,"r");
        $tWord = @trim(@fread($fhandle, filesize($filep)));
    @fclose($fhandle);
    $ntWord = preg_replace("/[a-zA-Z]/", " &#x25C9; ", $tWord);

    /* Appends a message from current sender to match's private chat box */
    $todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
    $todaysBoxHandle = @fopen($todaysBox, "a");
    if(!$todaysBoxHandle)
        die("Cannot open Private ShoutBox !!");

    $RealText = urldecode(str_replace("\n", " ", $_POST['Shout']));

    /* Replace the normal smileys in the message */
    $NormalSmileyList = array("bug", ";)", ";-)", ":)", ":-|", ":|", ":'(", ":(", ":-)", ":-(", "b-)", "b)", "8)", "8-)", ":d", ":-d", "lol", "rofl", ":-o", ":o", "o_o", "x-(", "x(", "<3", ":p", ":-p",
                                ":*", ":-*", ":s", ":-s");
    for($i = 0; $i < count($NormalSmileyList); ++$i)
        $RealText = str_ireplace($NormalSmileyList[$i], "<img src=\"images/smileys/" . urlencode($NormalSmileyList[$i]) . ".gif\"/>", $RealText);

    /* Replace the escaped smileys in the message */
    $EscapedSmileyList = array("\m/", ":-/", ":/", ":\\", ":-\\", "..|. .|..");
    for($i = 0; $i < count($EscapedSmileyList); ++$i)
        $RealText = str_ireplace($EscapedSmileyList[$i], "<img src=\"images/smileys/escaped/" . urlencode(urlencode($EscapedSmileyList[$i])) . ".gif\"/>", $RealText);

    $matchFileHandler = @fopen("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time", "r");
        $matchTime = intval(@trim(@fread($matchFileHandler, filesize("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time"))));
    @fclose($matchFileHandler);
    $stabilityTimeLeft = (60 - (time() - $matchTime));

    /* Check if the sentence contains the answer */
    $FilteredText = preg_replace("/\b".$tWord."\b/iu", $ntWord, $RealText);
    if($_SESSION['SMT_Role'] == "Active") {
        @fwrite($todaysBoxHandle, "<span class='chatSuper'>" . $_SESSION['SMT_UName'] . "</span> : " . $FilteredText . "<br>\n");
    } else {
        if(($FilteredText != $RealText) && (!file_exists("../UsersDB/GamePoints/".$_SESSION['SMT_UId']))) {
            @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> : " . $FilteredText . "<br>\n");
            @fwrite($todaysBoxHandle, "<center><img src=\"images/chat_icons/star.gif\"><span class='chatUser'> " . $_SESSION['SMT_UName'] . " got it! <img src=\"images/chat_icons/star.gif\"></center>\n");
            $fhandle = @fopen("../UsersDB/GamePoints/".$_SESSION['SMT_UId'], "w");
                @fwrite($fhandle, "20");
            @fclose($fhandle);
            if($stabilityTimeLeft > 10) {
                $handle = @fopen("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time", "w");
                    @fwrite($handle, time()-50);
                @fclose($handle);
                @fwrite($todaysBoxHandle, "<center><span style='color: #900;'> TIME FORWARDED! 10 SECS LEFT!!</center>\n");
            }
        } else
            @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> : " . $FilteredText . "<br>\n");
    }

    @fclose($todaysBoxHandle);
?>
