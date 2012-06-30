<?php
	include_once('../Utilities/LDAP.php');
	include_once('../Utilities/MAIL.php');
	
	$retArray = array();
	if(file_exists("Members/" . $_POST['log']))
	{
		$retArray["status"] = false;
		$retArray["message"] = "A verified account with this username already exists !";
	}
	else if(file_exists("ToBeVerified/" . $_POST['log']))
	{
		$retArray["status"] = false;
		$retArray["message"] = "An account with this username is already awaiting verification !";
	}
	else if(($ldap_res = @do_ldap_search($_POST['log'])) === false)
	{
		$retArray["status"] = false;
		$retArray["message"] = "No such LDAP user is found on IIT-B Active Directory.";
	}
	else
	{
		$to = $ldap_res['givenname'][0];
		$at = $ldap_res['mail'][0];
		$verifyCode = "SMT_U-" . md5(base64_encode($to));
		
		$handle = @fopen("PendingHashes/$verifyCode", "w");
			@fwrite($handle, $_POST['log']);
		@fclose($handle);
		
		$exp_dn = explode("=", $ldap_res['dn']);
		$exp_dn = explode(",", $exp_dn[3]);
		$handle = @fopen("ToBeVerified/" . $_POST['log'], "w");
			@fwrite($handle, strtoupper($ldap_res['employeetype'][0]) . " - " . $exp_dn[0] . " : " . $ldap_res['employeenumber'][0] . "\n");
			@fwrite($handle, $to . "\n");
			@fwrite($handle, base64_encode(md5($_POST['pwd'])));
		@fclose($handle);
		
		@sendVerifyMail($to, $at, $verifyCode);
		
		$retArray["status"] = true;
		$retArray["message"] = "Thanks, $to. A verification link has been sent to $at.";
	}
		
	header("Content-type: application/json");
	echo json_encode($retArray);
	die();
?>