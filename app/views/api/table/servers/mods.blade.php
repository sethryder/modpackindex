{
<?php $i = 0 ?>
"id": -1,
"fieldErrors": [],
"sError": "",
"aaData": [
@foreach ($mods as $index => $mod)
    {
    "DT_RowId": "row_{{ $i }}",
    "mod": "{{{ $mod['name'] }}}",
    "version": "{{{ $mod['version'] }}}"
    }@if ($index+1 != count($mods)), @endif

    <?php $i++ ?>
@endforeach]
}