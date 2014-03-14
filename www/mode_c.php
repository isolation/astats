<?php
    if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    if (isset($page)) {
        if (preg_match("/^(list|top|chan|status|ustats)$/", $page)) {
            if ($page == "list") {
                include('chan_list.php');
            } else if ($page == "top") {
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
