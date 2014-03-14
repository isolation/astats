<?php
    if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    if (isset($page)) {
        if (preg_match("/^(list|chan)$/", $page)) {
            if ($page == "list") {
                include('topic_list.php');
            } else if ($page == "chan") {
                include('topic_chan.php');
            }
        } else {
            print "unknown topic page type<br />\n";
        }
    }
?>
