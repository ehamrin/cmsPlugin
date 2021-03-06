<h1>Error log</h1>
<div class="table-wrapper">
<table>
    <tr>
        <th>Date</th>
        <th>Type</th>
        <th>IP-address</th>
        <th>Session</th>
    </tr>
@foreach($logs as $log)
    <tr>
        <td>{{ $log->date }}</td>
        <td class="exception-type">
            {{ substr(explode('<br />',nl2br($log->exception))[0], 0, 70) }}
            <span class="exception-info error">
                <pre>{{ $log->exception }}</pre>
            </span>
        </td>
        <td>{{ $log->ip }}</td>
        <td>{{ $log->session }}</td>
    </tr>
@endforeach
</table>
</div>
