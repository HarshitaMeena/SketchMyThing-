<?php
    require_once "Utilities/reqAuth.php";

    /* If the user is not playing a game,
     * redirect him to proper page  */
    if(!isset($_SESSION['SMT_Busy'])) {
        header("Location: ./");
        die();
    }

    /* If the user is not is not in a match,
     * redirect him to proper page  */
    if(!isset($_SESSION['SMT_MID'])) {
        header("Location: ./");
        die();
    }

    /* Obtain the word of the current game */
    $filep = "SMT_Internal/Matches/MatchInfo/Words/" . $_SESSION['SMT_MID'] . ".word";
    $fhandle = @fopen($filep,"r");
        $tWord = @trim(@fread($fhandle, filesize($filep)));
    @fclose($fhandle);

    /* Obtain the host of the current game */
    $matchFileHandler = @fopen("SMT_Internal/Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host", "r");
        $matchHost = @trim(@fread($matchFileHandler, filesize("SMT_Internal/Matches/MatchInfo/Hosts/" . $_SESSION['SMT_MID'] . ".host")));
    @fclose($matchFileHandler);

    /* Update the user role according to
     * the back end settings of current game */
    if($matchHost == $_SESSION['SMT_UId'] && $_SESSION['SMT_Role'] != 'Active')
        $_SESSION['SMT_Role'] = 'Active';
    else if($matchHost != $_SESSION['SMT_UId'] && $_SESSION['SMT_Role'] == 'Active')
        $_SESSION['SMT_Role'] = 'Passive';
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sketch My Thing !</title>

    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/fancybox.css" rel="stylesheet" type="text/css" />
    <link href="css/anythingslider.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/easing.js"></script>
    <script type="text/javascript" src="js/fancybox.js"></script>
    <script type="text/javascript" src="js/anythingslider.js"></script>
    <script type="text/javascript" src="js/commonScreenInfo.js"></script>
    <?php if($_SESSION['SMT_Role'] == 'Active') echo '    <script type="text/javascript" src="js/activeScreenInfo.js"></script>'; else echo '    <script type="text/javascript" src="js/passiveScreenInfo.js"></script>';?>
</head>

<body>
    <div id="container">
        <div id="content">
            <h1 class="smt" style="font-size: 3em; text-align: center;" id="SMThead">Sketch My Thing !</h1>
            <div style="margin-top: 10px;"></div>
            <h2 style="padding-left: 3em; float:left;">Hello, <i><?php echo $_SESSION['SMT_UName']; ?></i> !</h2><h2 style="padding-right: 16em; float: right;"><i>Version : 0.9b</i></h2>
            <div id="nonFartContent" style="clear: both;">
            <ul id="slider">
                <li>
                    <div>
                        <div id="sketchOverlayMsg"><span id="wordMessage" class="overlayText">You have to <?php if($_SESSION['SMT_Role'] == 'Active') echo "draw"; else echo "guess"; ?> <span id="THEWORD"><?php if($_SESSION['SMT_Role'] == 'Active') echo $tWord; else echo preg_replace("/[a-zA-Z]/", "*", $tWord); ?></span> ..</span><span id="timeLeft" class="overlayText">00:06</span></div>
                        <?php if($_SESSION['SMT_Role'] == 'Active') echo
'                        <div id="sketchOverlayTools">
                            <div class="selectedTool colorTool" id="blackColorTool"></div>
                            <div class="colorTool" id="redColorTool"></div>
                            <div class="colorTool" id="yellowColorTool"></div>
                            <div class="colorTool" id="greenColorTool"></div>
                            <div class="colorTool" id="cyanColorTool"></div>
                            <div class="colorTool" id="blueColorTool"></div>
                            <div class="colorTool" id="magentaColorTool"></div>
                            <div class="colorTool" id="whiteColorTool"></div>
                            <div style="float:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            <div class="selectedTool sizeTool" id="sizeTool2"></div>
                            <div class="sizeTool" id="sizeTool4"></div>
                            <div class="sizeTool" id="sizeTool6"></div>
                            <div class="sizeTool" id="sizeTool8"></div>
                            <div class="sizeTool" id="sizeTool12"></div>
                            <div style="float:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            <button class="goodbutton">CLEAR</button>
                            <button class="okbutton">GIVE UP</button>
                            <button class="badbutton">DITCH !</button>
                        </div>';?>
                        <canvas id="sketchArea" width="550" height="411"></canvas>
                        <div id="chatBoxDiv" class="shoutBox"></div>
                        <textarea id="chatMsgDiv" class="shoutMsg" placeholder="TYPE YOUR GUESS HERE ..."></textarea>
                    </div>
                </li>
                <li>
                    <div id="PointsTable"></div>
                </li>
                <li>
                    <div>
                        <div id="shoutBoxDiv" class="shoutBox"></div>
                        <textarea id="shoutMsgDiv" class="shoutMsg" placeholder="SHOUT HERE !"></textarea>
                        <button id="shoutBtn">SHOUT !</button>
                    </div>
                </li>
            </ul>
            </div>
            <h2 style="text-align: center; font-size: 1.2em;">Sketch My Thing ! Designed and Created by Saswat Padhi, BTech II CSE IIT-Bombay [2011]</h2>
        </div><!-- / content -->
    </div><!-- / container -->
</body>
</html>
