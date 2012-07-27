<?php
    require_once dirname(dirname(__FILE__)) . "/Utilities/reqAuth.php";

    $ClearIndex = strrpos($_POST['STATE'], "L ");
    if($ClearIndex !== false)
    {
        $MouseMovement = substr($_POST['STATE'], $ClearIndex);
        $handle = @fopen("Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev", "w");
            @fwrite($handle, $MouseMovement);
        @fclose($handle);
    }
    else
    {
        $handle = @fopen("Matches/MatchInfo/Drawings/" . $_SESSION['SMT_MID'] . ".mev", "a");
            @fwrite($handle, $_POST['STATE']);
        @fclose($handle);
    }
?>
