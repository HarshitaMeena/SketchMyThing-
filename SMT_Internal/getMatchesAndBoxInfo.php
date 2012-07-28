<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/reqAuth.php";

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

    if(!isset($_POST['NoPlayers'])) { // no game running right now
        if(!isset($_POST['onlyMyMatch'])) { // Populates player match join list
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
        } else { // joined but no game running
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
    } else { // game in progress
        $matchFileHandler = @fopen("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time", "r");
            $matchTime = intval(@trim(@fread($matchFileHandler, filesize("Matches/MatchInfo/Times/" . $_SESSION['SMT_MID'] . ".time"))));
        @fclose($matchFileHandler);
        $stabilityTimeLeft = (60 - (time() - $matchTime));

        $matchFileHandler = @fopen("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "r");
            $matchHost = @trim(@fread($matchFileHandler, filesize("Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host")));
        @fclose($matchFileHandler);

        if($stabilityTimeLeft < 1) {
            $handle = @fopen("Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word", "r");
                $word = @trim(@fread($handle, filesize("Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word")));
            @fclose($handle);

            $handle = @fopen("Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev", "w");
            @fclose($handle);

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

            $correctPlayers = array();
            foreach($data as $player) {
                $playerfile = "../UsersDB/GamePoints/$player";
                if(!file_exists($playerfile))
                    continue;
                $fhandle = @fopen($playerfile, "r");
                    $points = @intval(@trim(@fread($fhandle, filesize($playerfile))));
                @fclose($fhandle);
                $correctPlayers[$player] = $points;
                @unlink($playerfile);
                $fhandle = @fopen("../UsersDB/MatchPoints/$player", "r");
                    $points += @intval(@trim(@fread($fhandle, filesize("../UsersDB/MatchPoints/$player"))));
                @fclose($fhandle);
                $fhandle = @fopen("../UsersDB/MatchPoints/$player", "w");
                    @fwrite($fhandle, $points);
                @fclose($fhandle);
            }

            $todaysBox = "ShoutBox/Private/" . $_SESSION['SMT_MID'] . ".box";
            $todaysBoxHandle = @fopen($todaysBox, "a");
                @fwrite($todaysBoxHandle, "<br><center>\n");
                @fwrite($todaysBoxHandle, "<span class='chatSuper'>Word of the round was: </span><span class='chatUser'>$word</span><br>\n");

            @fwrite($todaysBoxHandle, "<span class='chatSuper'>Player(s) who got this one:</span><br>\n");
            foreach($correctPlayers as $player=>$point) {
                $handle = @fopen("../UsersDB/Members/" . $player, "r");
                    $line = @fgets($handle);
                    $fullName = @trim(@fgets($handle));
                @fclose($handle);

                @fwrite($todaysBoxHandle, "<span class='chatUser'>$fullName [$point]</span><br>\n");
            }
            if(!count($correctPlayers))
                @fwrite($todaysBoxHandle, "<span class='chatUser'> NO ONE !!</span><br>\n");
            @fwrite($todaysBoxHandle, "</center>".var_dump($correctPlayers)."<br>\n");
            @fclose($todaysBoxHandle);

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
