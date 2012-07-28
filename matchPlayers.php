<?php
    require_once "Utilities/reqAuth.php";

    if(isset($_SESSION['SMT_Busy'])) {
        header("Location: gameScreen.php");
        die();
    }

    if(!isset($_SESSION['SMT_MID'])) {
        header("Location: ./");
        die();
    }

    $matchFileHandler = @fopen("SMT_Internal/Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "r");
        $matchHost = @trim(@fread($matchFileHandler, filesize("SMT_Internal/Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host")));
    @fclose($matchFileHandler);
    if($matchHost == $_SESSION['SMT_UId'])
        $_SESSION['SMT_Role'] = 'Active';
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <title>Sketch My Thing !</title>

    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!--<link href="css/fancybox.css" rel="stylesheet" type="text/css" />-->
    <link href="css/anythingslider.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/easing.js"></script>
    <!--<script type="text/javascript" src="js/fancybox.js"></script>-->
    <script type="text/javascript" src="js/autocolumn.js"></script>
    <script type="text/javascript" src="js/anythingslider.js"></script>
    <script type="text/javascript" src="js/matchInfo.js"></script>
</head>

<body>
    <div id="container">
        <div id="content">
            <div class="smt" style="margin-top: 64px; font-size: 7em; text-align: center;" id="SMThead">Sketch My Thing!</div>
            <h2 style="margin-left: 3.5em; float:left;">Hello, <i><?php echo $_SESSION['SMT_UName']; ?></i> !</h2><h2 style="float: right; margin-right: 7em;">Version : 1.01b</h2>
            <div id="nonFartContent" style="clear: both;">
                <ul id="slider">
                    <li>
                        <div>
                            <div class="matchTable">
                                <center style="font-size: 1.6em;">You have joined match <span class='smts'><?php echo $_SESSION['SMT_MID']; ?></span><br><br><span class='smts'>~: PLAYERS :~</span></center>
                                <div id="matchPlayersList"></div>
                                <br>
                                <center><?php if($_SESSION['SMT_Role'] == 'Active') {echo '<button id="StartMatchBtn">Start Match</button> ';}?><button id="LeaveMatchBtn">Ditch Match!</button></center>
                            </div>
                            <div id="chatBoxDiv" class="shoutBox"></div>
                            <textarea id="chatMsgDiv" class="shoutMsg" placeholder="MATCH GROUP CHAT ..."></textarea>
                        </div>
                    </li>
                    <li>
                        <div>
                            <div id="shoutBoxDiv" class="shoutBox"></div>
                            <textarea id="shoutMsgDiv" class="shoutMsg" placeholder="SHOUT HERE !"></textarea>
                            <button id="shoutBtn">SHOUT !</button>
                        </div>
                    </li>
                </ul>
            </div><br>
            <div style="text-align: center; font-size: 1em; color: grey;"><span class="smt">Sketch My Thing!</span> Designed and Created (from scratch!) by Saswat Padhi & Harshita Meena, BTech III CSE IIT-Bombay [2012]</div>
        </div><!-- / content -->
    </div><!-- / container -->
</body>
</html>
