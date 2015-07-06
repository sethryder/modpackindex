{
<?php $i = 0 ?>
"id": -1,
"fieldErrors": [],
"sError": "",
"aaData": [
@foreach ($modpacks as $index => $modpack)
    {
    "DT_RowId": "row_{{ $i }}",
    "name": "{{ $modpack['name'] }}",
    "icon_html":  {{ $modpack['icon_html'] }},
    "deck": {{ $modpack['deck'] }},
    "links": {{ $modpack['links'] }},
    "creators": "{{ $modpack['creators'] or "N/A" }}",
    "version": "{{ $modpack['version'] }}"
    }@if ($index+1 != count($modpacks)), @endif

    <?php $i++ ?>
@endforeach]
}