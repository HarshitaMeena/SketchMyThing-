<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/reqAuth.php";

    $my_value = $_SESSION['SMT_UId'];
    function filterFunc ($element) {
        global $my_value;
        return ($element != $my_value);
    }

    if(isset($_POST['Leaving'])) {
        if(isset($_SESSION['SMT_Busy'])) {
            if($_SESSION["SMT_Role"] == "Active") {
                $matchFileHandler = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "r");
                    $oldPlayers = @explode("\n", @trim(@fread($matchFileHandler, filesize("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt"))));
                @fclose($matchFileHandler);

                $todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
                $todaysBoxHandle = @fopen($todaysBox, "a");
                $oldPlayers = array_values(array_filter($oldPlayers, "filterFunc"));
                if(count($oldPlayers) > 1) {
                    $matchFileHandler = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "w");
                        @fwrite($matchFileHandler, @implode("\n", $oldPlayers) . "\n");
                    @fclose($matchFileHandler);

                    $matchFileHandler = @fopen("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "w");
                        @fwrite($matchFileHandler, $oldPlayers[0]);
                    @fclose($matchFileHandler);

                    @fwrite($todaysBoxHandle, "<span class='chatSuper'>" . $_SESSION['SMT_UName'] . "</span> has ditched match @ <span class='chatTime'>[" . date("h:i A") . "]</span><br>\n");
                } else {
                    @unlink("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt");
                    @unlink("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host");
                    @unlink("Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev");
                    @unlink("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time");
                    @unlink("Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word");
                }
                @fclose($todaysBoxHandle);
            } else {
                $matchFileHandler = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "r");
                    $oldPlayers = @explode("\n", @trim(@fread($matchFileHandler, filesize("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt"))));
                @fclose($matchFileHandler);

                $todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
                $todaysBoxHandle = @fopen($todaysBox, "a");
                $oldPlayers = array_values(array_filter($oldPlayers, "filterFunc"));
                if(count($oldPlayers) > 1) {
                    $matchFileHandler = @fopen("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt", "w");
                        @fwrite($matchFileHandler, @implode("\n", $oldPlayers) . "\n");
                    @fclose($matchFileHandler);

                    @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> has ditched match @ <span class='chatTime'>[" . date("h:i A") . "]</span><br>\n");
                } else {
                    @unlink("Matches/Locked/" . $_SESSION['SMT_MID'] . ".Lsmt");
                    @unlink("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host");
                    @unlink("Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev");
                    @unlink("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time");
                    @unlink("Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word");
                }
                @fclose($matchFileHandler);
                @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> has ditched match @ <span class='chatTime'>[" . date("h:i A") . "]</span><br>\n");
                @fclose($todaysBoxHandle);
            }
        } else {
            $matchFileHandler = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "r");
                $oldPlayers = @explode("\n", @trim(@fread($matchFileHandler, filesize("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt"))));
            @fclose($matchFileHandler);

            $todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
            $todaysBoxHandle = @fopen($todaysBox, "a");
            $oldPlayers = array_values(array_filter($oldPlayers, "filterFunc"));
            if(count($oldPlayers)) {
                $matchFileHandler = @fopen("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt", "w");
                    @fwrite($matchFileHandler, @implode("\n", $oldPlayers) . "\n");
                @fclose($matchFileHandler);

                if($_SESSION['SMT_Role'] == 'Active') {
                    $matchFileHandler = @fopen("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "w");
                        @fwrite($matchFileHandler, $oldPlayers[0]);
                    @fclose($matchFileHandler);

                    @fwrite($todaysBoxHandle, "<span class='chatSuper'>" . $_SESSION['SMT_UName'] . "</span> has ditched match @ <span class='chatTime'>[" . date("h:i A") . "]</span><br>\n");
                } else
                    @fwrite($todaysBoxHandle, "<span class='chatUser'>" . $_SESSION['SMT_UName'] . "</span> has ditched match @ <span class='chatTime'>[" . date("h:i A") . "]</span><br>\n");
            } else {
                @unlink("Matches/Waiting/" . $_SESSION['SMT_MID'] . ".Wsmt");
                @unlink("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host");
                @unlink("Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev");
                @unlink("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time");
                @unlink("Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word");
            }
            @fclose($todaysBoxHandle);
        }
        unset($_SESSION['SMT_Role']);
        unset($_SESSION['SMT_MID']);
    }
?>
