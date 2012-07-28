<?php

/*
    Freshly cooked Mailing Script :P
    Pure, Socket operations \o/

    Saswat Padhi
*/

include_once dirname(dirname(__FILE__)) . "/config.php";

function cmd($smtp, $str, $report=true )
{
    $ret = @fwrite($smtp, $str."\r\n" );
    if( $report === true )
        @fread($smtp, 512);
}

function sendVerifyMail($to_whom, $at_address, $verifyCode)
{
    global $smtp_user, $smtp_pass;

    $message = array();
    $message[] = "Hi <b>" . $to_whom . "</b> !!<br>";
    $message[] = "<br>";
    $message[] = "Thanks for registering on <b>Sketch My Thing</b> :-)<br>";
    $message[] = "To confirm your LDAP id on SMT server, please visit the link below :<br>";
    $message[] = "<br>";
    $message[] = "<a href='http://www.cse.iitb.ac.in/~saswatpadhi/SMT/UsersDB/confirmUser.php?code=$verifyCode'>http://www.cse.iitb.ac.in/~saswatpadhi/SMT_New/UsersDB/confirmUser.php?code=$verifyCode</a><br>";
    $message[] = "<br>";
    $message[] = "Regards,<br>";
    $message[] = "SMT Admin";

    $smtp = @fsockopen( "tcp://smtp-auth.iitb.ac.in", 25, $errno, $errstr);
    if(!$smtp)
        return false;

    @fread($smtp, 512);

    cmd($smtp, "EHLO localhost" );
    cmd($smtp, "STARTTLS" );

    if( !@stream_socket_enable_crypto( $smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT ))
        return false;

    cmd($smtp, "EHLO localhost" );
    cmd($smtp, "AUTH LOGIN" );
    cmd($smtp, base64_encode($smtp_user));
    cmd($smtp, base64_encode($smtp_pass));
    cmd($smtp, "MAIL FROM: <saswatpadhi@cse.iitb.ac.in>" );
    cmd($smtp, "RCPT TO: <$at_address>" );
    cmd($smtp, "DATA" );

    cmd($smtp, "MIME-Version: 1.0",false);
    cmd($smtp, "Content-Type: text/html",false);
    cmd($smtp, "Date: ".date("r"), false );
    cmd($smtp, "From: SMT Admin <saswatpadhi@cse.iitb.ac.in>", false );
    cmd($smtp, "Subject: SMT Registration", false );
    cmd($smtp, "To: $at_address", false );

    for( $x=0; $x<sizeof($message); $x++ )
    cmd($smtp, $message[$x], false );

    cmd($smtp, "." );
    cmd($smtp, "QUIT" );
    @fclose( $smtp );
}
?>
