    <?php
        if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    ?>
    <p>
    <a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=0&amp;sort=$sort&amp;order=$order" ?>">total</a> |
    <a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=1&amp;sort=$sort&amp;order=$order" ?>">today</a> | 
    <a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=2&amp;sort=$sort&amp;order=$order" ?>">this week</a> | 
    <a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=3&amp;sort=$sort&amp;order=$order" ?>">this month</a>
    </p>
    <h4>channel activity <?php print $types[$type] . " on " . $nett ?></h4>
    <table id="hor-minimalist-a" summary="top channels">
    <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=chan&amp;order=$chan_order" ?>">channel</a><?php print $chan_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=letters&amp;order=$letters_order" ?>">letters</a><?php print $letters_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=words&amp;order=$words_order" ?>">words</a><?php print $words_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=line&amp;order=$line_order" ?>">lines</a><?php print $line_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=actions&amp;order=$actions_order" ?>">actions</a><?php print $actions_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=smileys&amp;order=$smileys_order" ?>">smileys</a><?php print $smileys_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=kicks&amp;order=$kicks_order" ?>">kicks</a><?php print $kicks_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=modes&amp;order=$modes_order" ?>">modes</a><?php print $modes_icon ?></th>
    <th scope="col"><a href="<?php print "?m=c&amp;p=$page&amp;net=$encoded_net&amp;type=$type&amp;sort=topics&amp;order=$topics_order" ?>">topics</a><?php print $topics_icon ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    if ($dbh = new PDO("sqlite:$cv_database")) {
        $query = "SELECT chan, letters, words, line, actions, smileys, kicks, modes, topics FROM
            lenoracstats WHERE type = ? AND line > 0 AND net = ? ORDER BY $sort $order LIMIT 25";
        $stmt = $dbh->prepare("$query");
        //$stmt = $dbh->prepare('SELECT chan, letters, words, line, actions, smileys, kicks, modes, topics
        //    FROM lenoracstats WHERE type = ? AND line > 0 AND net = ? ORDER BY letters DESC LIMIT 25');
        if ($stmt->execute(array($type, $netdb))) {
            while ($row = $stmt->fetch()) {
                $i++;
                $encoded_chan = rawurlencode($row[0]);
                $format = "<tr>\n        ".     // open row
                    "<td>%d</td>\n        ".    // #
                    "<td><a href=\"?m=c&amp;p=chan&amp;net=%s&amp;sort=letters&amp;type=%d&amp;chan=%s\">%s</a></td>\n        ".    // channel name
                    "<td>%d</td>\n        ".    // letters
                    "<td>%d</td>\n        ".    // words
                    "<td>%d</td>\n        ".    // lines
                    "<td>%d</td>\n        ".    // actions
                    "<td>%d</td>\n        ".    // smileys
                    "<td>%d</td>\n        ".    // kicks
                    "<td>%d</td>\n        ".    // modes
                    "<td>%d</td>\n    ".        // topics
                    "</tr>\n    ";              // end row
                printf($format, $i, $encoded_net, $type, $encoded_chan, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
            }
        }
    }
?>
</tbody>
    </table>
