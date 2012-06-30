<?php
	include_once "Utilities/AUTH.php";

	ensureLoggedIn();
	
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
	
	<!--[if lt IE 7]>
	 <style type="text/css">
	 .dock img { behavior: url(iepngfix.htc) }
	 </style>
	<![endif]-->
	
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/fancybox.css" rel="stylesheet" type="text/css" />
	<link href="css/anythingslider.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript" src="js/iutil.js"></script>
	<script type="text/javascript" src="js/fisheye.js"></script>
	<script type="text/javascript" src="js/fancybox.js"></script>
	<script type="text/javascript" src="js/autocolumn.js"></script>
	<!-- PNG FIX for IE6 -->
	<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
	<!--[if lte IE 6]>
		<script type="text/javascript" src="js/pngfix/supersleight-min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="js/anythingslider.js"></script>
	<script type="text/javascript" src="js/matchInfo.js"></script>
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
					<div class="matchTable">
						<center style="font-size: 1.6em;">You have joined match <b><?php echo $_SESSION['SMT_MID']; ?></b><br><br><b>~: PLAYERS :~</b></center>
						<div id="matchPlayersList"></div>
						<br>
						<center><?php if($_SESSION['SMT_Role'] == 'Active') {echo '<button id="StartMatchBtn">Start This Match !</button> ';}?><button id="LeaveMatchBtn">Ditch This Match !</button></center>
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
			</div>
			<h2 style="text-align: center; font-size: 1.2em;">Sketch My Thing ! Designed and Created by Saswat Padhi, BTech II CSE IIT-Bombay [2011]</h2>
		</div><!-- / content -->		
	</div><!-- / container -->

<!--bottom dock -->
<div class="dock" id="dock2">
	<div class="dock-container2">
		<a class="dock-item2" href="./"><span>Home</span><img src="images/home.png" alt="home" /></a>
		<a class="dock-item2" href="#"><span></span><img alt="" /></a>
	  	<a class="dock-item2" href="#SMT_UnderConstruction" id="SMTProfileDockIcon" title="Sketch My Thing ! :: Profile"><span>Profile</span><img src="images/email.png" alt="settings" /></a>
		<a class="dock-item2" href="#SMT_UnderConstruction" id="SMTSettingsDockIcon" title="Sketch My Thing ! :: Settings"><span>Settings</span><img src="images/email.png" alt="settings" /></a> 
		<a class="dock-item2" href="#"><span></span><img alt="" /></a>
		<a class="dock-item2" href="#SMT_Help" id="SMTHelpDockIcon" title="Sketch My Thing ! :: Help"><span>Help</span><img src="images/portfolio.png" alt="help" /></a> 
		<a class="dock-item2" href="#SMT_About" id="SMTAboutDockIcon" title="Sketch My Thing ! :: About"><span>About</span><img src="images/portfolio.png" alt="about" /></a>
		<a class="dock-item2" href="#"><span></span><img alt="" /></a>
	  	<a class="dock-item2" href="#"><span>Logout</span><img src="images/portfolio.png" alt="logout" /></a>
  	</div>
</div>

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