<?php
    if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    if (!isset($page)) {
        $page = "top";
    }
    if (isset($page)) {
        if (preg_match("/(ustats|top)/", $page)) {
            if ($page == "ustats") {
                include('user_ustats.php');
            } else if ($page == "top") {
                include('user_top.php');
            }
        } else {
            print "unknown page type<br />\n";
        }
    }
?>
