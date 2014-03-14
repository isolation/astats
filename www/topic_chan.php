    <?php
        if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    ?>
    <h4>topic listing for <?php print $chandb ?> (total)</h4>
    <table id="hor-minimalist-t" summary="topic listing">
        <thead>
            <tr>
                <th>#</th>
                <th>setter</th>
                <th class="date">date (UTC)</th>
                <th>topic</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $i = 0;
    if ($dbh = new PDO("sqlite:$cv_database")) {
        $stmt = $dbh->prepare('SELECT uname, time, topic
            FROM lenoratopic WHERE type = 0 AND chan = ? AND net = ? ORDER BY time DESC');
        if ($stmt->execute(array($chandb, $netdb))) {
            while ($row = $stmt->fetch()) {
                $i++;
                $format = "        <tr>\n        ".     // open row
                    "        <td>%d</td>\n        ".    // #
                    "        <td>%s</td>\n        ".    // nick
                    "        <td>%s</td>\n    ".        // time
                    "            <td>%s</td>\n    ".        // topic
                    "        </tr>\n    ";              // end row
                printf($format, $i, $row[0], gmdate("d-m-Y H:i:s", $row[1]), htmlspecialchars($row[2]));
            }
        }
    }
?>
</tbody>
    </table>
