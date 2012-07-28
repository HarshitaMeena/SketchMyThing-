<?php
    include_once "Utilities/AUTH.php";

    $loggedIn = isLoggedIn();

    if(isset($_SESSION['SMT_MID'])) {
        header("Location: matchPlayers.php");
        die();
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sketch My Thing !</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/fancybox.css" rel="stylesheet" type="text/css" />
<?php
    if(!$loggedIn) {
        echo '<link href="css/slide.css" rel="stylesheet" type="text/css" />';
    } else {
        echo '<link href="css/anythingslider.css" rel="stylesheet" type="text/css" />';
    }
?>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript" src="js/fancybox.js"></script>
<?php
    if(!$loggedIn) {
        echo '<script type="text/javascript" src="js/slide.js"></script>';
        echo '<script type="text/javascript" src="js/userInfo.js"></script>';
    } else {
        echo '<script type="text/javascript" src="js/anythingslider.js"></script>';
        echo '<script type="text/javascript" src="js/gameInfo.js"></script>';
    }
?>
</head>

<body>
<?php
    if(!$loggedIn) {
        echo
'<!-- Panel -->
<div id="toppanel">
    <div id="panel">
        <div class="content clearfix">
            <div class="left">
                <div class="smt" style="font-size: 2.5em;">Sketch My Thing!</div>
                <h2>Restricted to IIT-B students ONLY.</h2>
                <p class="grey">Sketch My Thing! is currently open only to IIT-B students, because of technical issues of handling huge number of users, while hosting on CSE server.</p>
                <h2>But ...</h2>
                <p class="grey">You can drop us a mail at our CSE mail IDs : saswatpadhi@cse.iitb.ac.in / harshita@cse.iitb.ac.in for an account.</p>
            </div>
            <div class="left">
                <!-- Login Form -->
                <form class="clearfix" action="#" method="post">
                    <h1>Member Login</h1>
                    <label class="grey" for="log">LDAP Username:</label>
                    <input class="field" type="text" name="log" id="log" value="" size="23" />
                    <label class="grey" for="pwd">SMT Password:</label>
                    <input class="field" type="password" name="pwd" id="pwd" size="23" />
                    <br><br><br>
                    <input type="submit" name="submit" value="Login" class="bt_login" />
                    <a class="lost-pwd" href="#">Lost your password?</a>
                </form>
            </div>
            <div class="left right">
                <!-- Register Form -->
                <form id="RegForm" method="post" autocomplete="off">
                    <h1>Sign Up!</h1>
                    <label class="grey" for="LDAPlog">LDAP Username:</label>
                    <input class="field" type="text" name="LDAPlog" id="LDAPlog" value="" size="23" />
                    <label class="grey" for="SMTpwd">SMT Password:</label>
                    <input class="field" type="password" name="SMTpwd" id="SMTpwd" size="23" />
                    <label>A verification link will be mailed to your GPO account.</label>
                    <input type="submit" name="submit" value="Register" class="bt_register" />
                </form>
            </div>
        </div>
        <center>
            <div id="panelMSG">
            </div>
        </center>
    </div> <!-- /login -->

    <!-- The tab on top -->
    <div class="tab">
        <ul class="login">
            <li class="left">&nbsp;</li>
            <li>Hello Guest!</li>
            <li class="sep">|</li>
            <li id="toggle">
                <a id="open" class="open">Log In | Register</a>
                <a id="close" style="display: none;" class="close">Close Panel</a>
            </li>
            <li class="right">&nbsp;</li>
        </ul>
    </div> <!-- / top -->

</div> <!--panel -->
    '; }
?>

    <div id="container">
        <div id="content">
            <div class="smt" style="margin-top: <?php if(!$loggedIn) echo "64px"; else echo "8px";?>; font-size: 7em; text-align: center;" id="SMThead">Sketch My Thing!</div>
            <?php if($loggedIn) echo '<h2 style="margin-left: 3.5em; float:left;">Hello, <i>' . $_SESSION['SMT_UName'] . '</i> !</h2>'; ?>
            <h2 style="float: right; margin-right: 7em;">Version : 1.01b</h2>
            <div id="nonFartContent" style="clear: both;">
            <?php if(!$loggedIn) echo
            '<p style="font-size: 1em; width: 780px; margin-left: 90px;">An addictive multi-player game where one person draws something while the other players guess what the person is trying to draw .. like a massive online version of pictionary. Inspired by OMGPOP Draw My Thing! (which sadly doesn\'t work inside the IITB network because of the great NetMon proxy). Sketch My Thing! is entirely coded in PHP and JS, using the latest HTML5 technologies.<br><br>The major improvement that we have brought in this is the HTML5 canvas instead of Flash, and AJAX polling instead of background connections through ports.</p><br>
            <h1 style="text-align: center; font-size: 1.6em;">Registered Members : <span id="MemCount">Counting ..</span> | Online Members : <span id="OLCount">Counting ..</span></h1>
            <br>'; else echo
            '<ul id="slider">
                <li>
                    <p style="font-size: 2em;">
                        Being addicted to <span class="smt">Sketch My Thing!</span> is simple as A B C.. <br><br>
                        <span class="smt">A </span> new match has to be started by you (button below) <br>
                        <span class="smt">B</span>e the host of the match, invite / kick out players <br>
                        <span class="smt">C</span>reate your drawing and let your friends guess what it is <br><br>
                        <span style="font-size: 0.7em; text-align: center;">The "host of the match", who draws stuff; keeps cycling amongst all players in the match.</span>
                    </p>
                    <center><button id="createNewMatchBtn" style="color: blue; margin-top: 12px; height: 84px; font: bold 48px customfont; border-radius: 16px; -moz-border-radius: 16px;">new smt match!</button></center>
                </li>
                <li>
                    <div class="gamesTable">
                    <table cellspacing="0" cellpadding="0" border="0" width="860px">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="0" width="858px" style="font-size: 1.5em;">
                                    <tr>
                                        <th class="GameID">Game ID</th>
                                        <th class="Players">Players</th>
                                        <th class="JoinGame">Availability</th>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 858px; height: 25.8em; overflow: auto;">
                                    <table cellspacing="0" cellpadding="0" border="0" id="mainGameTable">
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>
                </li>
                <li>
                    <div>
                        <div id="shoutBoxDiv" class="shoutBox"></div>
                        <textarea id="shoutMsgDiv" class="shoutMsg" placeholder="SHOUT HERE !"></textarea>
                        <button id="shoutBtn">SHOUT !</button>
                    </div>
                </li>
            </ul><br>';?>
            </div>
            <div style="text-align: center; font-size: 1em; color: grey;"><span class="smt">Sketch My Thing!</span> Designed and Created (from scratch!) by Saswat Padhi & Harshita Meena, BTech III CSE IIT-Bombay [2012]</div>
        </div><!-- / content -->
    </div><!-- / container -->
    <script type="text/javascript" >
            <?php
            if(!$loggedIn)
            {
                if(isset($_POST['defMsg']))
                    echo "$('#panelMSG').html('" . $_POST['defMsg'] . "');\n";

                if(isset($_POST['defStatus'])) {
                    if($_POST['defStatus'])            echo "$('#panelMSG').addClass('goodmsg');\n";
                    else                               echo "$('#panelMSG').addClass('badmsg');\n";

                    echo 'setTimeout(function() {$("#open").click();}, 500);';
                }
            }
            ?>
    </script>
</body>
</html>
