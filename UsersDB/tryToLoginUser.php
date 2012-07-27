<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/AUTH.php";

    $retArray = array();
    if(file_exists("Members/" . $_POST['log'])) {
        $handle = @fopen("Members/" . $_POST['log'], "r");
            $line = @trim(@fgets($handle));
            $fullName = @trim(@fgets($handle));
            $passwordHash = @trim(@fgets($handle));
        @fclose($handle);

        if($passwordHash == base64_encode(md5($_POST['pwd']))) {
            $retArray["status"] = true;
            $retArray["message"] = "You are now logged in ! :-)";

            activateUser($_POST['log'], $fullName);

            $handle = @fopen("MemberLogins/" . $_POST['log'], "a");
                @fwrite($handle, " IN : " . date("D_d-m-Y h:i A") . " @ " . getClientIPAddress() . "\n");
            @fclose($handle);

            $handle = @fopen("LastAjax/" . $_POST['log'], "w");
                @fwrite($handle, time());
            @fclose($handle);
        }
        else {
            $retArray["status"] = false;
            $retArray["message"] = "The password you entered doesn't match with the one on server. Forgot yout password ?";
        }
    }
    else if(file_exists("ToBeVerified/" . $_POST['log'])) {
        $retArray["status"] = false;
        $retArray["message"] = "An account linked with this LDAP id, is awaiting verification of GPO mail account.";
    }
    else {
        $retArray["status"] = false;
        $retArray["message"] = "No such user " . $_POST['log'] . " is registered on SMT.";
    }

    header("Content-type: application/json");
    echo json_encode($retArray);
    die();
?>
