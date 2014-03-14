    <?php
        if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    ?>
    <p>
        | <a href="<?php print "?m=$mode&amp;p=$page&amp;net=$encoded_net&amp;type=0&amp;user=".$encoded_user; ?>">total</a> | 
        <a href="<?php print "?m=$mode&amp;p=$page&amp;net=$encoded_net&amp;type=1&amp;user=".$encoded_user; ?>">today</a> | 
        <a href="<?php print "?m=$mode&amp;p=$page&amp;net=$encoded_net&amp;type=2&amp;user=".$encoded_user; ?>">this week</a> | 
        <a href="<?php print "?m=$mode&amp;p=$page&amp;net=$encoded_net&amp;type=3&amp;user=".$encoded_user; ?>">this month</a> | <br /><br />
        <img src="<?php print "bargraph.php?m=u&amp;net=$encoded_net&amp;type=$type&amp;chan=global&amp;user=".$encoded_user; ?>" width="560" height="200" alt="[user bar graph]" />
    </p>
    <h4><?php print "graph for nickname ". $_GET["user"] . " on " . $_GET["net"] . " (" . $types[$_GET["type"]] . ")"; ?></h4>
    <table id="hor-minimalist-c" summary="user stats">
    <thead>
    <tr>
    <th scope="col">type</th>
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
        $stmt = $dbh->prepare('SELECT letters, words, line, actions, smileys, kicks, modes, topics
            FROM lenoraustats WHERE uname = ? AND chan = \'global\' AND net = ? ORDER BY type ASC');
        if ($stmt->execute(array($unamedb, $netdb))) {
            while ($row = $stmt->fetch()) {
                $format = "<tr>\n        <td>%s</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n    </tr>\n    ";
                printf($format, $types[$i], $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
                $i++;
            }
        }
    }
?>
</tbody>
    </table>
