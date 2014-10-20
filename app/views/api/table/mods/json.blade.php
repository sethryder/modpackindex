{
<?php $i=0 ?>
  "id": -1,
  "fieldErrors": [],
  "sError": "",
  "aaData": [
@foreach ($mods as $index => $mod)
  {
    "DT_RowId": "row_{{ $i }}",
    "name": "{{ $mod['name'] }}",
    "deck": "{{ $mod['deck'] }}",
    "links": {{ $mod['links'] }},
    "authors": "{{ $mod['authors'] or "N/A" }}",
    "versions": "{{ $mod['versions'] }}"
  }@if ($index+1 != count($mods)), @endif

<?php $i++ ?>
@endforeach]
}