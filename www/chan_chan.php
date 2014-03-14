    <?php
    if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }

    ?>

    <p>
        <a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=$sort&amp;type=0&amp;chan=".$encoded_chan; ?>">total</a> | 
        <a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=$sort&amp;type=1&amp;chan=".$encoded_chan; ?>">today</a> | 
        <a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=$sort&amp;type=2&amp;chan=".$encoded_chan; ?>">this week</a> | 
        <a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=$sort&amp;type=3&amp;chan=".$encoded_chan; ?>">this month</a>
    </p>
    <p>
        <img class="topgraph" src="<?php print "bargraph.php?m=c&amp;type=$type&amp;net=$encoded_net&amp;chan=".$encoded_chan; ?>" width="560" height="200" alt="[channel bar graph]" />
    </p>
    <h4><?php print "information for channel " . $chandb . " (" . $types[$type] . ")"; ?></h4>
    <table id="hor-minimalist-a" summary="channel statistics">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=uname&amp;order=$uname_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">nick<?php print $uname_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=letters&amp;order=$letters_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">letters<?php print $letters_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=words&amp;order=$words_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">words<?php print $words_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=line&amp;order=$line_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">lines<?php print $line_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=actions&amp;order=$actions_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">actions<?php print $actions_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=smileys&amp;order=$smileys_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">smileys<?php print $smileys_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=kicks&amp;order=$kicks_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">kicks<?php print $kicks_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=modes&amp;order=$modes_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">modes<?php print $modes_icon; ?></a></th>
        <th scope="col"><a href="<?php print "?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=topics&amp;order=$topics_order&amp;type=$type&amp;chan=".$encoded_chan; ?>">topics<?php print $topics_icon; ?></a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    //print "type $type sort $sort order $order";
    $i = 0;
    if ($dbh = new PDO("sqlite:$cv_database")) {
        // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        // if there is a page number specified in the URL, use that. otherwise, default to page 1
        if (isset($page_num)) {
            $db_offset = $page_num;
        } else {
            $db_offset = 1;
        }

        // get a count of how many users match the current query (used for pagination)
        $stmt = $dbh->prepare('SELECT COUNT(*) AS count FROM lenoraustats WHERE chan = ? AND type = ? AND line > 0 AND net = ?');
        if ($stmt->execute(array($chandb, $type, $encoded_net))) {
            $row = $stmt->fetch();
            $num_res = $row[0];
            $pages_need = ceil(($num_res / 25));
        }

        // calculate the value in the 'LIMIT ?,25' portion of our database query
        $db_offset = (($db_offset - 1) * 25);

        // query is double quoted because $sort and $order cannot be done with a pure prepared statement
        $query = "SELECT uname, letters, words, line, actions, smileys, kicks, modes, topics
            FROM lenoraustats WHERE chan = ? AND type = ? AND line > 0 AND net = ? ORDER BY $sort $order LIMIT ?,25";

        // build as much as we possibly can into a prepared statement
        $stmt = $dbh->prepare("$query");

        // this code, when enabled, gives the top 3 users gold/medal/bronze icons instead of 1/2/3
        // $gold = "<img src=\"img/medal_gold_2.png\" width=\"12\" height=\"12\" alt=\"[gold medal]\" title=\"first place!\" />";
        // $silver = "<img src=\"img/medal_silver_2.png\" width=\"12\" height=\"12\" alt=\"[silver medal]\" title=\"second place!\" />";
        // $bronze = "<img src=\"img/medal_bronze_2.png\" width=\"12\" height=\"12\" alt=\"[bronze medal]\" title=\"third place!\" />";

        if ($stmt->execute(array($chandb, $type, $encoded_net, $db_offset))) {
            // start numbering at our current offset (so page 2 doesn't start at 1 all over again)
            $i = $db_offset;
            while ($row = $stmt->fetch()) {
                $i++;

                // more code related to the gold/silver/bronze thing
                // $idk = $i;
                // if ($i == 1) { $idk = $gold; }
                // if ($i == 2) { $idk = $silver; }
                // if ($i == 3) { $idk = $bronze; }

                $encoded_nick = rawurlencode($row[0]);
                $format = "<tr>\n        ".     // tr start
                    "<td>%s</td>\n        ".    // $i
                    "<td><a href=\"?m=c&amp;p=ustats&amp;net=%s&amp;chan=%s&amp;user=%s&amp;type=0\">%s</a></td>\n        ".    // encoded nick, nick
                    "<td>%d</td>\n        ".    // letters
                    "<td>%d</td>\n        ".    // words
                    "<td>%d</td>\n        ".    // line
                    "<td>%d</td>\n        ".    // actions
                    "<td>%d</td>\n        ".    // smileys
                    "<td>%d</td>\n        ".    // kicks
                    "<td>%d</td>\n        ".    // modes
                    "<td>%d</td>\n    ".        // topics
                    "</tr>\n    ";              // tr end
                printf($format, $i, $encoded_net, $encoded_chan, $encoded_nick, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
            }
        }

        // this gets the timeadded date for the channel to put it into the pre-footer area provided by index.php
        $stmt = $dbh->prepare('SELECT timeadded FROM lenoracstats WHERE net = ? AND type = 0 AND chan = ?');
        if($stmt->execute(array($encoded_net, $chandb))) {
            $row = $stmt->fetch();
            $pre_footer = $chandb . " stats begin " . date("H:i:s d M Y", $row[0]) . " UTC || ";
        }
    }
?>
</tbody>
    </table>
    <p class="pages"><?php if(($db_offset + 25) < $num_res) { $this_cap = $db_offset + 25; } else { $this_cap = $num_res; } print "(".($db_offset + 1)."-".$this_cap." of ".$num_res.")"; ?> Page:<?php 
        if (!isset($page_num)) { $page_num = 1; }
        for ($i = 1; $i <= $pages_need; $i++) {
            print " ";
            if ($i == $page_num) {
                print $i;
            } else {
                print "<a href=\"?m=c&amp;p=chan&amp;net=$encoded_net&amp;sort=$sort&amp;order=$order&amp;type=$type&amp;chan=".$encoded_chan."&amp;pn=$i\">$i</a>";
            }
        }
    ?></p>
