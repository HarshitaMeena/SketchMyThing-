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

<!--[if lt IE 7]>
 <style type="text/css">
 .dock img { behavior: url(iepngfix.htc) }
 </style>
<![endif]-->

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
<script type="text/javascript" src="js/iutil.js"></script>
<script type="text/javascript" src="js/fisheye.js"></script>
<script type="text/javascript" src="js/fancybox.js"></script>
  	<!-- PNG FIX for IE6 -->
  	<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
	<!--[if lte IE 6]>
		<script type="text/javascript" src="js/pngfix/supersleight-min.js"></script>
	<![endif]-->
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
				<h1 class="smt">Sketch My Thing !</h1>
				<h2>Restricted to IIT-B students ONLY.</h2>		
				<p class="grey">Sketch My Thing ! is currently open only to IIT-B students, because of technical issues of handling huge number of users, while hosting on CSE server.</p>
				<h2>But ...</h2>
				<p class="grey">You can drop me a mail at my CSE mail address : saswatpadhi@cse.iitb.ac.in for an account.</p>
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
			<h1 class="smt" style="font-size: 3em; text-align: center;" id="SMThead">Sketch My Thing !</h1>
			<div style="margin-top: 10px;"></div>
			<?php if($loggedIn) echo '<h2 style="padding-left: 3em; float:left;">Hello, <i>' . $_SESSION['SMT_UName'] . '</i> !</h2>'; ?><h2 style="padding-right: 16em; float: right;"><i>Version : 0.9b</i></h2>
			<div id="nonFartContent" style="clear: both;">
			<?php if(!$loggedIn) echo
			'<p class="highlight">Sketch My Thing is an addictive multi-player game where one person draws something while the other players guess what word they are trying to say, like a massive online version of pictionary.</p>
			<h1 style="text-align: center; font-size: 1.6em;">Registered Members : <span id="MemCount">Counting ..</span></h1>
			<br>'; else echo
			'<ul id="slider">
				<li>
					<p style="font: bold 24px Comic Sans MS;">
						Create a new match and invite your friends to join :)
					</p>
					<p style="font: 20px Comic Sans MS; text-align: justify;">
						When you start a match, you become the HOST of the match. You have the power to kick any player out of your match <b>before the game starts</b>.<br>
						Of course, you being the HOST, decide when to start the game ;-)<br><br>
						Further, you get to draw the first picture of the match.
					</p>
					<center><button id="createNewMatchBtn" style="margin-top: 12px; height: 84px; font: bold 32px Comic Sans MS;">New SMT Match !</button></center>	
				</li>
				<li>
					<div class="gamesTable">
					<table cellspacing="0" cellpadding="0" border="0" width="860px">
						<tr>
							<td>
								<table cellspacing="0" cellpadding="0" border="0" width="844px">
									<tr>
										<th class="GameID">Game_ID</th>
										<th class="Players">Players</th>
										<th class="JoinGame">Join ??</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<div style="width: 844px; height: 25.8em; overflow: auto;">
									<table cellspacing="0" cellpadding="0" border="0" width="844px" id="mainGameTable">
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
			</ul>';?>
			</div>
			<h2 style="text-align: center; font-size: 1.2em;">Sketch My Thing ! Designed and Created by Saswat Padhi, BTech II CSE IIT-Bombay [2011]</h2>
		</div><!-- / content -->		
	</div><!-- / container -->

<!--bottom dock -->
<div class="dock" id="dock2">
  <div class="dock-container2">
  <a class="dock-item2" href="./"><span>Home</span><img src="images/home.png" alt="home" /></a>
<?php
	if($loggedIn) {
		echo
  		'<a class="dock-item2" href="#"><span></span><img alt="" /></a>
  	<a class="dock-item2" href="#SMT_UnderConstruction" id="SMTProfileDockIcon" title="Sketch My Thing ! :: Profile"><span>Profile</span><img src="images/email.png" alt="settings" /></a>
  <a class="dock-item2" href="#SMT_UnderConstruction" id="SMTSettingsDockIcon" title="Sketch My Thing ! :: Settings"><span>Settings</span><img src="images/email.png" alt="settings" /></a>';}?> 
  		<a class="dock-item2" href="#"><span></span><img alt="" /></a>
  <a class="dock-item2" href="#SMT_Help" id="SMTHelpDockIcon" title="Sketch My Thing ! :: Help"><span>Help</span><img src="images/portfolio.png" alt="help" /></a> 
  <a class="dock-item2" href="#SMT_About" id="SMTAboutDockIcon" title="Sketch My Thing ! :: About"><span>About</span><img src="images/portfolio.png" alt="about" /></a>
<?php
	if($loggedIn) {
		echo
  		'<a class="dock-item2" href="#"><span></span><img alt="" /></a>
  <a class="dock-item2" href="#"><span>Logout</span><img src="images/portfolio.png" alt="logout" /></a>';}?> 
  </div>
</div>

<script type="text/javascript" >
		<?php
		if(!$loggedIn)
		{
			if(isset($_POST['defMsg']))
				echo "$('#panelMSG').html('" . $_POST['defMsg'] . "');\n";
				
			if(isset($_POST['defStatus'])) {
				if($_POST['defStatus'])			echo "$('#panelMSG').addClass('goodmsg');\n";
				else									echo "$('#panelMSG').addClass('badmsg');\n";

				echo 'setTimeout(function() {$("#open").click();}, 500);';
			}
		}
		?>
</script>
<script type="text/javascript" src="js/init.js"></script>

<!-- Fancybox hidden content -->
<div class="hiddenFancyBoxes">
	<div id="SMT_UnderConstruction" class="allFancyBoxes">
		<br><br><br><br><center><h1 class="smt">Under Construction</h1></center>
	</div>
	<div id="SMT_Help" class="allFancyBoxes">
		<br><span class="smt">&nbsp;&nbsp;Sketch My Thing&nbsp;&nbsp;</span> is really simple, yet interesting multi-player game.
	</div>
	<div id="SMT_About" class="allFancyBoxes">
		<h1 class="smt">&nbsp;Sketch My Thing !</h1><br><br>
		Version: 0.9b<br>
		Created: 28th October, 2011<br>
		<br>
		Designed and created by SASWAT PADHI, (CSE II) IIT-Bombay.
	</div>
</div>
<!-- /Fancybox hidden content -->
</body>
</html>