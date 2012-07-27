<?php
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "AUTH.php";

    ensureLoggedIn();

    $handle = @fopen("../UsersDB/LastAjax/" . $_SESSION['SMT_UId'], "w");
        @fwrite($handle, time());
    @fclose($handle);
?>
