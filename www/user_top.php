    <?php
        if(!isset($_GET["type"])) { $type = 3; }
        if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    ?>
    <h4>user activity <?php print $types[$type] . " on " . $nett ?></h4>
    <table id="hor-minimalist-a" summary="top users">
    <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">channel</th>
    <th scope="col">letters</th>
    <th scope="col">words</th>
    <th scope="col">lines</th>
    <th scope="col">actions</th>
    <th scope="col">smileys</th>
    <th scope="col">kicks</th>
    <th scope="col">modes</th>
    <th scope="col">topics</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    if ($dbh = new PDO("sqlite:$cv_database")) {
        $stmt = $dbh->prepare('SELECT uname, letters, words, line, actions, smileys, kicks, modes, topics
            FROM lenoraustats WHERE type = ? AND line > 0 AND chan = \'global\' AND net = ? ORDER BY letters DESC LIMIT 25');
        if ($stmt->execute(array($type, $netdb))) {
            while ($row = $stmt->fetch()) {
                $i++;
                $m_encoded_user = rawurlencode($row[0]);
                $format = "<tr>\n        ".     // open row
                    "<td>%d</td>\n        ".    // #
                    "<td><a href=\"?m=u&amp;p=ustats&amp;net=%s&amp;user=%s&amp;type=%d\">%s</a></td>\n        ".    // user name
                    "<td>%d</td>\n        ".    // letters
                    "<td>%d</td>\n        ".    // words
                    "<td>%d</td>\n        ".    // lines
                    "<td>%d</td>\n        ".    // actions
                    "<td>%d</td>\n        ".    // smileys
                    "<td>%d</td>\n        ".    // kicks
                    "<td>%d</td>\n        ".    // modes
                    "<td>%d</td>\n    ".        // topics
                    "</tr>\n    ";              // end row
                printf($format, $i, $encoded_net, $m_encoded_user, $type, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
            }
        }
    }
?>
</tbody>
    </table>
