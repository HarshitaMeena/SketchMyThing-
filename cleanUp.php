<?php
    function cleanUpUserData($user) {
        $locked = glob("SMT_Internal/Matches/Locked/*");
        $waiting = glob("SMT_Internal/Matches/Waiting/*");

        foreach($locked as $file) {
            $players = file_get_contents($file);
            if(strpos($players, $user) != false) {
                $handle = @fopen($file, "w");
                    @fwrite($handle, implode('\n', array_filter(array_map("trim", explode('\n', str_replace($user, "", $players))))));
                @fclose($handle);
            }
        }

        foreach($waiting as $file) {
            $players = file_get_contents($file);
            if(strpos($players, $user) != false) {
                $handle = @fopen($file, "w");
                    @fwrite($handle, implode('\n', array_filter(array_map("trim", explode('\n', str_replace($user, "", $players))))));
                @fclose($handle);
            }
        }
    }
    
    function detectDeadUsers() {
        $retArray = new array();
        $files = glob("UsersDB/LastAjax/*");
        foreach($files as $file) {
            $handle = @fopen($file, "r");
                $time = @trim(@fgets($handle));
            @fclose($handle);
            if(time() - $time > 30)
                $retArray[] = basename($file);
        }
    }
    
    detectDeadUsers();
?>
