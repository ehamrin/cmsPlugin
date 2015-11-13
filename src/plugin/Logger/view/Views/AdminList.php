<h1>Error log</h1>
<table>
    <tr>
        <th>Date</th>
        <th>Type</th>
        <th>IP-address</th>
        <th>Session</th>
    </tr>
<?php foreach($logs as $log): ?>
    <tr>
        <td><?= $log->date; ?></td>
        <td class="exception-type">
            <?php
            $line = explode('<br />',nl2br($log->exception))[0];
            $short = substr($line, 0, 70);
            echo $short;
            if($line != $short){
              echo '...';
            }
            ?>
            <span class="exception-info error">
                <pre><?= $log->exception; ?></pre>
            </span>
        </td>
        <td><?= $log->ip; ?></td>
        <td><?= $log->session; ?></td>
    </tr>

<?php endforeach; ?>
</table>
