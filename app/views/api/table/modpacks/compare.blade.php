{
<?php $i = 0 ?>
"id": -1,
"fieldErrors": [],
"sError": "",
"aaData": [
@foreach ($mods as $index => $mod)
    <?php $ii = 0 ?>
    {
    "DT_RowId": "row_{{ $i }}",
    "name": "{{ $mod['name'] }}",
    @foreach($modpacks as $modpack_id => $modpack)
        @if (in_array($modpack_id, $mod['packs']))
            "{{ $modpack_id }}": "X"
        @else
            "{{ $modpack_id }}": ""
        @endif
        @if ($ii+1 != count($modpacks)), @endif
        <?php $ii++; ?>
    @endforeach
    }@if ($i+1 != count($mods)), @endif
    <?php $i++; ?>
@endforeach]
}