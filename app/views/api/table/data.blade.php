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
@if (($type == 'modpacks') || ($type == 'launchers') || ($type == 'modpackfinder') || $type == 'modmodpacks')
    { sType: 'launcher', aTargets: [4] }
@endif

@if ($type == 'servers')
    { sType: 'population', aTargets: [4] }
@endif
],
"aoColumns": [
@foreach ($columns as $index => $column)
    { "mData": "{{ $column }}" }@if ($index+1 != count($columns)), @endif
@endforeach],
"fnInitComplete": function(oSettings, json) {
$(this).parents ('.dataTables_wrapper').find ('.dataTables_filter input').prop ('placeholder', 'Search...').addClass ('form-control input-sm')
},
@if (($type == 'modpacks') || ($type == 'launchers') || ($type == 'modpackfinder') || $type == 'modmodpacks')
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
    var icon_object = aData.icon_html;
    if (typeof icon_object !== 'undefined') {
    $('td:eq(4)', nRow).html("<a href='"+icon_object.link+"'>"+"<img src='"+icon_object.icon+"'></a>");
    }}
@endif
});
@if ($table_fixed_header == true)new $.fn.dataTable.FixedHeader( table_1 );
@endif
@if (($type == 'modpacks') || ($type == 'launchers') || ($type == 'modpackfinder') || $type == 'modmodpacks')
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
@endif

@if ($type == 'servers')
    function getCount(value) {
    var pattern = /^([0-9]*)(\s)\/(\s)(?:[0-9]*)$/g
    var match = pattern.exec(value);
    if (match) return parseInt(""+match[1]);
    // should never return 0 unless the regex fails
    return parseInt("0");
    }

    // reliant on player count maintaining the same format
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "population-asc": function( a, b ) {
    var x = getCount(a);
    var y = getCount(b);

    return ((x < y) ? -1 : ((x > y) ?  1 : 0));
    },

    "population-desc": function(a,b) {
    var x = getCount(a);
    var y = getCount(b);

    return ((x < y) ?  1 : ((x > y) ? -1 : 0));
    }
    } );
@endif
});