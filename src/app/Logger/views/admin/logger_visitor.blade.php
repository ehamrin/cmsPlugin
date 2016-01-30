<h1>Visitor log</h1>
<p>Total visitors: <?= count($logs); ?></p>
<div class="table-wrapper">
<table>
    <tr>
        <th>Date</th>
        <th>Visits</th>
        <th>IP-address</th>
        <th>User Agent</th>
        <th>Logged in</th>
    </tr>
    @foreach($logs as $log)
        <tr>
            <td>{{ $log->date }}</td>
            <td class="exception-type">
                {{ $log->count }}
                <span class="exception-info info">
                <pre>
                @foreach($log->visited as $visit)
                    {{ $visit->date }} {{ $visit->info['REQUEST_METHOD'] }} {{ $visit->info["REQUEST_URI"] }}<br/>
                @endforeach
                </pre>
            </span>
            </td>
            <td>{{ $log->info['REMOTE_ADDR'] }}</td>
            <td>
                {{ $log->info['HTTP_USER_AGENT'] or 'None' }}
            </td>
            <td>{{ $log->user }}</td>
        </tr>
    @endforeach
</table>
</div>