    <?php
        if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
    ?>
    <table id="hor-minimalist-a" summary="ImgR Record Record">
    <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">nick</th>
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
            FROM lenoraustats WHERE chan = ? AND type = ? ORDER BY letters DESC LIMIT 25');
        if ($stmt->execute(array($chandb, $type))) {
            while ($row = $stmt->fetch()) {
                $i++;
                $format = "<tr>\n        <td>%d</td>\n        <td>%s</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n        <td>%d</td>\n    </tr>\n    ";
                printf($format, $i, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
            }
        }
    }
?>
</tbody>
    </table>
