<?php

	@session_start();
	@date_default_timezone_set("Asia/Kolkata");
	
	function activateUser($LDUSER, $UserName) {
		@session_regenerate_id();
		$_SESSION['SMT_UId'] = $LDUSER;
		$_SESSION['SMT_UName'] = $UserName;
		$_SESSION['SMT_UA'] = md5($_SERVER['HTTP_USER_AGENT'] . "..SMT\m/");
	}	
	
	function isLoggedIn() {
		@session_regenerate_id();
		if(isset($_SESSION['SMT_UId']))
			if(isset($_SESSION['SMT_UName']))
				if(isset($_SESSION['SMT_UA']))
					if($_SESSION['SMT_UA'] == md5($_SERVER['HTTP_USER_AGENT'] . "..SMT\m/"))
						return true;
		
		logoutUser();
		return false;
	}
	
	function ensureLoggedIn() {
		if(!isLoggedIn())
		{
			$currentLocation = "./";
			while(!file_exists($currentLocation . "iepngfix.htc"))
				$currentLocation = $currentLocation . "../";
			header("Location: " . $currentLocation);
			die();
		}
	}
	
	function logoutUser() {
		$_SESSION = array();
		@session_destroy();
	}
	
	function getClientIPAddress() {
   	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
      	if (array_key_exists($key, $_SERVER) === true) {
         	foreach (explode(',', $_SERVER[$key]) as $ip) {
            	if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
               	return $ip;
               }
            }
         }
		}
	}
?>