<?php
    if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    if (isset($page)) {
        if (preg_match("/^(top|chan|status|ustats)$/", $page)) {
            if ($page == "top") {
                include('chan_top.php');
            } else if ($page == "chan") {
                include('chan_chan.php');
            } else if ($page == "status") {
                include('chan_status.php');
            } else if ($page == "ustats") {
                include('chan_ustats.php');
            }
        } else {
            print "unknown chan page<br />\n";
        }
    }
?>
