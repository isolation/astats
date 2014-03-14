<?php
    if(!defined('BEEN_INCLUDED')) { die('cannot directly access this file'); }
?>
<div class="hcontainer">
<div class="floatLeft">
<h4>top <?php print $nett; ?> channels this week</h4>
<table id="hor-minimalist-b" summary="top 3 channels this week">
<thead>
<tr>
<th scope="col">#</th>
<th scope="col">channel</th>
<th scope="col">lines</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;
if ($dbh = new PDO("sqlite:$cv_database")) {
    $stmt = $dbh->prepare('SELECT chan, line FROM lenoracstats WHERE type = 2 AND line > 0 AND net = ? ORDER BY line DESC LIMIT 3');
    if ($stmt->execute(array($netdb))) {
        while ($row = $stmt->fetch()) {
            $i++;
            $encoded_chan = rawurlencode($row[0]);
            $format = "<tr>\n        ".     // open tr
                "<td>%d</td>\n        ".    // $
                "<td><a href=\"?m=c&amp;p=chan&amp;net=%s&amp;sort=letter&amp;type=2&amp;chan=%s\">%s</a></td>\n        ".     // channel name
                "<td>%d</td>\n    ".        // lines
                "</tr>\n    ";              // end tr
            printf($format, $i, $encoded_net, $encoded_chan, $row[0], $row[1]);
        }
    }
}
?>
</tbody>
    </table>
</div>
<div class="floatRight">
<h4>top <?php print $nett; ?> users this week</h4>
<table id="hor-minimalist-b2" summary="top 10 users this week">
<thead>
<tr>
<th scope="col">#</th>
<th scope="col">nickname</th>
<th scope="col">lines</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;
if ($dbh = new PDO("sqlite:$cv_database")) {
    $stmt = $dbh->prepare('SELECT uname, line FROM lenoraustats WHERE type = 2 AND line > 0 AND chan = \'global\' AND net = ? ORDER BY line DESC LIMIT 10');
    if ($stmt->execute(array($netdb))) {
        while ($row = $stmt->fetch()) {
            $i++;
            $encoded_nick = rawurlencode($row[0]);
            $format = "<tr>\n        ".     // open tr
                "<td>%d</td>\n        ".    // $
                "<td><a href=\"?m=u&amp;p=ustats&amp;net=%s&amp;type=2&amp;user=%s\">%s</a></td>\n        ".     // channel name
                "<td>%d</td>\n    ".        // lines
                "</tr>\n    ";              // end tr
            printf($format, $i, $encoded_net, $encoded_nick, $row[0], $row[1]);
        }
    }
}
?>
</tbody>
    </table>
</div>
</div>
