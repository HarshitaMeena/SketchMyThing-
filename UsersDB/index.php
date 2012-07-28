<?php
    $directory = "Members/";
    if (glob($directory . "*") != false)
        $memcount = count(glob($directory . "*"));
    else
        $memcount = 0;

    if($_GET['action'] == 'get_count')
        die("" . $memcount);

    $directory = "LastAjax/";
    if (glob($directory . "*") != false)
        $memcount = count(glob($directory . "*"));
    else
        $memcount = 0;

    if($_GET['action'] == 'get_online')
        die("" . $memcount);

    header("Location: ../");
    die();
?>
