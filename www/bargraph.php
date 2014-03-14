<?php
/* This file is why the project is GPLv2.
 * It is based on phpDenora code which has
 * no simple (C) line for me to rip out and put here.
 * The relevant file is:
 *  phpdenora/libs/phpdenora/graphs/bar.php */

if (isset($_GET["chan"])) {
    $chan = htmlspecialchars($_GET["chan"]);
}

// need to set this so we can include our config file
define('BEEN_INCLUDED', TRUE);

include('lenora.inc.php');

if (isset($_GET["type"])) {
    if (preg_match("/[0-3]/", htmlspecialchars($_GET["type"]))) {
        $type = $_GET["type"];
    } else {
        $type = 1;
    }
} else {
    $type = 1;
}

if (isset($_GET["m"])) {
    if (preg_match("/^[uc]$/", htmlspecialchars($_GET["m"]))) {
        $mode = $_GET["m"];
    } else {
        die('I need a valid mode!');
    }
} else {
    die('I have to know the mode!');
}

if (isset($_GET["user"])) {
    $uname = htmlspecialchars($_GET["user"]);
}

if (isset($_GET["net"])) {
    $net = htmlspecialchars($_GET["net"]);
} else {
    $net = $cv_def_net;
}

require_once("jpgraph/jpgraph.php");
require_once("jpgraph/jpgraph_bar.php");

if ($mode == "c") {
    $filename = sprintf("bar_%s-t%s_%s", $chan, $type, "lenora");
} else if ($mode == "u") {
    $filename = sprintf("bar_%s-t%s_%s", $uname, $type, "lenora");
}

header("Content-Type: image/png");
header("Content-Disposition: inline; filename=$filename");

$pd_cache_bar = 0;

$graph = new Graph(560,200,$filename . "png",$pd_cache_bar);

$data = array();
$labels = array();


if ($dbh = new PDO("sqlite:$cv_database")) {
    if ($mode == "c") {
        $stmt = $dbh->prepare('SELECT time0,time1,time2,time3,time4,time5,time6,time7,time8,time9,time10,time11,time12,time13,time14,time15,time16,time17,time18,time19,time20,time21,time22,time23 FROM lenoracstats WHERE chan = ? AND type = ? AND net = ? ');
        if ($stmt->execute(array($chan, $type, $net))) {
            $row = $stmt->fetch();
            for ($i = 0; $i < 24; $i++) {
                $data[$i] = $row[$i];
                $labels[$i] = $i;
            }
        }
    } else if ($mode == "u") {
        $stmt = $dbh->prepare('SELECT time0,time1,time2,time3,time4,time5,time6,time7,time8,time9,time10,time11,time12,time13,time14,time15,time16,time17,time18,time19,time20,time21,    time22,time23 FROM lenoraustats WHERE uname = ? AND type = ? AND chan = ? AND net = ?');
        if($stmt->execute(array($uname, $type, $chan, $net))) {
            $row = $stmt->fetch();
            for ($i = 0; $i < 24; $i++) {
                $data[$i] = $row[$i];
                $labels[$i] = $i;
            }
        }
    }
}

$graph->SetScale("textlin");
$graph->yaxis->scale->SetGrace(20);

$graph->img->SetMargin(50,10,20,20);
$graph->xaxis->SetTickLabels($labels);
$graph->xaxis->SetTitle('Hour','high');
$graph->yaxis->SetTitle('lines','low');
$graph->yaxis->SetTitleMargin(35);

$bplot = new BarPlot($data);
$bplot->value->Show();
$bplot->value->SetFormat('%d');
$bplot->value->SetAngle(90);

$graph->SetMarginColor($tpl_graph_bg); # graph frame bg
$graph->SetFrame(true,$tpl_graph_bg,0); # graph frame margin
$graph->SetColor($tpl_graph_bg); # graph plot bg
$graph->xaxis->SetColor($tpl_graph_axis,$tpl_graph_labels); # x axis color
$graph->yaxis->SetColor($tpl_graph_axis,$tpl_graph_labels); # y axis color
$graph->ygrid->SetColor($tpl_graph_axis); # grid lines colors
$graph->xaxis->title->SetColor($tpl_graph_labels); # title colors
$graph->yaxis->title->SetColor($tpl_graph_labels); # title colors
$bplot->SetColor($tpl_bar_border); # bar border
$bplot->SetFillgradient($tpl_bar_fill[0],$tpl_bar_fill[1],GRAD_HOR); # bar fill
$bplot->value->SetColor($tpl_graph_labels); # value numbers above bars
$graph->footer->left->Set("(" . strftime($time_format_short) . " UTC)"); # display time of generation
$graph->footer->left->SetColor($tpl_graph_labels);
$graph->footer->left->SetFont(FF_FONT0);

$graph->Add($bplot);
$graph->Stroke();

?>
