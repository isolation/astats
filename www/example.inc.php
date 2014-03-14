<?php
if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }

// cv_database
//  set this to the absolute path of your auto bot's sqlite database
$cv_database = "/path/to/auto/database.db";

// cv_def_net
//  set this to the default network lenora should show
//  (all of them will still be shown, but this will be the default)
$cv_def_net = "MyNetwork";

/// image generation settings
$tpl_graph_labels = "#ADD0E5";
$tpl_graph_bg = "#333A40";
$tpl_graph_axis = "#4C5E5E";
$tpl_bar_border = "lightgray";
$tpl_bar_fill = array("#CDE4FF", "#4C5E5E");
$time_format_short = "%H:%M:%S %d %b %Y";
?>
