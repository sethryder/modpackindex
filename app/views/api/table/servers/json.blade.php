{
<?php $i = 0 ?>
"id": -1,
"fieldErrors": [],
"sError": "",
"aaData": [
@foreach ($servers as $index => $server)
    {
    "DT_RowId": "row_{{ $i }}",
    "options": {{ $server['options'] }},
    "name": {{ $server['name'] }},
    "modpack": {{ $server['modpack'] }},
    "server_address": "{{ $server['server_address'] }}",
    "players": "{{ $server['players']}}",
    "deck": "{{ $server['deck'] }}"
    }@if ($index+1 != count($servers)), @endif

    <?php $i++ ?>
@endforeach]
}