    <?php
        if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    ?>
    <h4>channels on <?php print $nett ?></h4>
    <table id="hor-minimalist-a" summary="topic listing">
    <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">channel</th>
    <th scope="col">topics</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    if ($dbh = new PDO("sqlite:$cv_database")) {
        $stmt = $dbh->prepare('SELECT chan, topics
            FROM lenoracstats WHERE type = 0 AND line > 0 AND net = ? ORDER BY topics DESC LIMIT 25');
        if ($stmt->execute(array($netdb))) {
            while ($row = $stmt->fetch()) {
                $i++;
                $encoded_chan = rawurlencode($row[0]);
                $format = "<tr>\n        ".     // open row
                    "<td>%d</td>\n        ".    // #
                    "<td><a href=\"?m=t&amp;p=chan&amp;net=%s&amp;chan=%s&amp;type=0\">%s</a></td>\n        ".    // user name
                    "<td>%d</td>\n    ".        // topics
                    "</tr>\n    ";              // end row
                printf($format, $i, $encoded_net, $encoded_chan, $row[0], $row[1]);
            }
        }
    }
?>
</tbody>
    </table>
