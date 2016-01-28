<h1>Verbose Visitor log</h1>
<p>Total requests: <?= count($logs); ?></p>
<div class="table-wrapper">
<table>
    <tr>
        <th>Date</th>
        <th>Method</th>
        <th>Info</th>
        <th>User Agent</th>
        <th>User</th>
        <th>Session</th>
    </tr>
    <?php foreach($logs as $log): ?>
        <tr>
            <td><?= $log->date; ?></td>
            <td><?= $log->info['REQUEST_METHOD']; ?></td>
            <td class="exception-type">
                <?= $log->info["REQUEST_URI"] ?>
            <span class="exception-info info">
                <pre><?php var_dump($log->info); ?></pre>
            </span>
            </td>
            <td><?= isset($log->info['HTTP_USER_AGENT']) ? $log->info['HTTP_USER_AGENT'] : ''; ?></td>
            <td><?= $log->user; ?></td>
            <td><?= $log->session; ?></td>
        </tr>

    <?php endforeach; ?>
</table>
</div>
