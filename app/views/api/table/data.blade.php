$(function() {
var table_1 = $('#{{ $table_id }}').dataTable ({
"bLengthChange": true,
"iDisplayLength": {{ $table_length or "15" }},
"sAjaxSource": "{{ $ajax_source }}",
@if ($table_order == false)"order": [],@endif
"sDom": '{{ $table_sdom }}',
"oLanguage": {
    "sEmptyTable": "{{{ $table_empty }}}",
},
"aoColumns": [
@foreach ($columns as $index => $column)
    { "mData": "{{ $column }}" }@if ($index+1 != count($columns)), @endif

@endforeach],
"fnInitComplete": function(oSettings, json) {
$(this).parents ('.dataTables_wrapper').find ('.dataTables_filter input').prop ('placeholder', 'Search...').addClass ('form-control input-sm')
}
});
@if ($table_fixed_header == true)new $.fn.dataTable.FixedHeader( table_1 );
@endif
})