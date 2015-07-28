{
<?php $i = 0 ?>
"id": -1,
"fieldErrors": [],
"sError": "",
"aaData": [
@foreach ($players as $index => $player)
    {
    "DT_RowId": "row_{{ $i }}",
    "name": "{{{ $player['name'] }}}"
    }@if ($index+1 != count($players)), @endif

    <?php $i++ ?>
@endforeach]
}