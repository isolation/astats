<?php
    $start = microtime(true);
    // this must be defined for any of our include files
    //  to listen to us
    define('BEEN_INCLUDED', TRUE);

    // get our config variables
    include('lenora.inc.php');

    // if the user value is set, this stores in two ways:
    // $chant is formatted to be used in page titles
    //  - example: $chant :: network :: lenora
    //  - (the trailing " :: " are added right now to
    //      simplify code down in the page)
    // $unamedb is cleaned to be used in actual database
    //  queries 
    if (isset($_GET["chan"])) {
        $chant = htmlspecialchars($_GET["chan"]) . ' :: ';
        $chandb = htmlspecialchars($_GET["chan"]);
        $encoded_chan = rawurlencode($_GET["chan"]);
    }

    // type index:
    // 0 - total
    // 1 - day
    // 2 - week
    // 3 - month
    // default value is 0 (total)
    if (isset($_GET["type"])) {
        if (preg_match("/^[0-3]$/", htmlspecialchars($_GET["type"]))) {
            $type = $_GET["type"];
        } else {
            $type = 0;
        }
    } else {
        $type = 0;
    }

    // mode index:
    // u - user-related things
    // c - channel-related things
    // h - home
    // a - about page
    // t - topic history
    // if nothing is set, value is set to h (home)
    // if something invalid is set, value is changed to h (home)
    if (isset($_GET["m"])) {
        if (preg_match("/^[uchat]$/", htmlspecialchars($_GET["m"]))) {
            $mode = $_GET["m"];
        } else {
            $mode = "h";
        }
    } else {
        $mode = "h";
    }

    // if the p (page) value is set, we decode it and store it
    //  for any children page that we call
    if (isset($_GET["p"])) {
        $page = htmlspecialchars($_GET["p"]);
    }

    // if the user value is set, this stores it in two ways:
    // $unamet is formatted to be used in page titles
    //  - example: $unamet :: network :: lenora
    //  - (the trailing " :: " are added right now to
    //      simplify code down in the page)
    // $unamedb is cleaned to be used in actual database
    //  queries
    if (isset($_GET["user"])) {
        $unamet = htmlspecialchars($_GET["user"]) . ' :: ';
        $unamedb = htmlspecialchars($_GET["user"]);
        $encoded_user = rawurlencode($_GET["user"]);
    }

    // the network to focus our current queries on
    // the default value will eventually become a conf setting
    if (isset($_GET["net"])) {
        $nett = htmlspecialchars($_GET["net"]);
        $netdb = htmlspecialchars($_GET["net"]);
        $encoded_net = rawurlencode($_GET["net"]);
    } else {
        $nett = $cv_def_net;
        $netdb = $cv_def_net;
        $encoded_net = rawurlencode("$cv_def_net");
    }
    $and_net = "&amp;net=" . $encoded_net;

    // this is to clean out the page number argument
    if (isset($_GET["pn"])) {
        if (preg_match("/^[[:digit:]]+$/", htmlspecialchars($_GET["pn"]))) {
            $page_num = htmlspecialchars($_GET["pn"]);
        }
    }
        
    // this array is used to map the current type to a
    //  descriptive string on some pages. being placed
    //  here because many pages use it
    $types = array("total", "today", "this week", "this month");

    if (isset($_GET["sort"])) {
        if (preg_match("/^(uname|chan|letters|words|line|actions|smileys|kicks|modes|topics)$/", $_GET["sort"])) {
            $sort = $_GET["sort"];
            if (isset($_GET["order"])) {
                if (preg_match("/^(asc|desc)$/", $_GET["order"])) {
                    $order = $_GET["order"];
                }
            } else {
                $order = "desc";
            }
        } else {
            $sort = "letters";
            $order = "desc";
        }
    } else {
        $sort = "letters";
        $order = "desc";
    }

    if (isset($order)) {
        if (!preg_match("/^(asc|desc)$/", $order)) {
            $order = "desc";
        }
    }

    if ($order == "desc") {
        $icon = "<img src=\"img/arrow_down.png\" width=\"12\" height=\"12\" alt=\"descending arrow\" title=\"descending\" />";
        $rev_order = "asc";
    } else {
        $icon = "<img src=\"img/arrow_up.png\" width=\"12\" height=\"12\" alt=\"ascending arow\" title=\"ascending\" />";
        $rev_order = "desc";
    }

    if ($sort == "uname") {
        $uname_order = $rev_order;
        $uname_icon = $icon;
    } else {
        $uname_order = $order;
        $uname_icon = '';
    }

    if ($sort == "chan") {
        $chan_order = $rev_order;
        $chan_icon = $icon;
    } else {
        $chan_order = $order;
        $chan_icon = '';
    }

    if ($sort == "letters") {
        $letters_order = $rev_order;
        $letters_icon = $icon;
    } else {
        $letters_order = $order;
        $letters_icon = '';
    }

    if ($sort == "words") {
        $words_order = $rev_order;
        $words_icon = $icon;
    } else {
        $words_order = $order;
        $words_icon = '';
    }

    if ($sort == "line") {
        $line_order = $rev_order;
        $line_icon = $icon;
    } else {
        $line_order = $order;
        $line_icon = '';
    }

    if ($sort == "actions") {
        $actions_order = $rev_order;
        $actions_icon = $icon;
    } else {
        $actions_order = $order;
        $actions_icon = '';
    }

    if ($sort == "smileys") {
        $smileys_order = $rev_order;
        $smileys_icon = $icon;
    } else {
        $smileys_order = $order;
        $smileys_icon = '';
    }

    if ($sort == "kicks") {
        $kicks_order = $rev_order;
        $kicks_icon = $icon;
    } else {
        $kicks_order = $order;
        $kicks_icon = '';
    }

    if ($sort == "modes") {
        $modes_order = $rev_order;
        $modes_icon = $icon;
    } else {
        $modes_order = $order;
        $modes_icon = '';
    }

    if ($sort == "topics") {
        $topics_order = $rev_order;
        $topics_icon = $icon;
    } else {
        $topics_order = $order;
        $topics_icon = '';
    }

?>
<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html version="-//W3C//DTD XHTML 1.1//EN"
      xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.w3.org/1999/xhtml
                          http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd"
>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="dh.css" />
    <title><?php if (isset($unamet)) { echo $unamet; } ?><?php if (isset($chant)) { echo $chant; } ?><?php echo $nett ?> :: Lenora</title>
</head>
<body>
<div id="wrapper">
<p>
<a href="?m=h<?php echo $and_net ?>">home</a> || <a href="?m=c&amp;p=top<?php echo $and_net ?>">channel list</a> || <a href="?m=u&amp;p=top<?php echo $and_net ?>">user list</a> || <a href="?m=t&amp;p=list<?php echo $and_net ?>">topic list</a> || <a href="?m=a">about</a>
</p>
<?php
    if (!isset($mode)) { $mode = "h"; }
    if ($mode == "u") {
        include('mode_u.php');
    } else if ($mode == "c") {
        include('mode_c.php');
    } else if ($mode == "h") {
        include('mode_h.php');
    } else if ($mode == "a") {
        include('mode_a.php');
    } else if ($mode == "t") {
        include('mode_t.php');
    }
?>
    <p class="footer"><?php if(isset($pre_footer)) { echo $pre_footer; } ?>page generated in <?php $end = microtime(true); printf("%.3f", $end - $start); ?> seconds</p>
</div>
</body>
</html>
