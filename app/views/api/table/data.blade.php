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
"aoColumnDefs": [
{ sType: 'launcher', aTargets: [4] }
],
"aoColumns": [
@foreach ($columns as $index => $column)
    { "mData": "{{ $column }}" }@if ($index+1 != count($columns)), @endif

@endforeach],
"fnInitComplete": function(oSettings, json) {
$(this).parents ('.dataTables_wrapper').find ('.dataTables_filter input').prop ('placeholder', 'Search...').addClass ('form-control input-sm')
},
"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
var icon_object = aData.icon_html;
$('td:eq(4)', nRow).html("<a href='"+icon_object.link+"'>"+"<img src='"+icon_object.icon+"'></a>");
}});
@if ($table_fixed_header == true)new $.fn.dataTable.FixedHeader( table_1 );
@endif
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
"launcher-asc": function( a, b ) {
var x = a.title.toLowerCase();
var y = b.title.toLowerCase();

return ((x < y) ? -1 : ((x > y) ?  1 : 0));
},

"launcher-desc": function(a,b) {
var x = a.title.toLowerCase();
var y = b.title.toLowerCase();

return ((x < y) ?  1 : ((x > y) ? -1 : 0));
}
} );
});