$(function() {
  var table_1 = $('#table-1').dataTable ({
    "sAjaxSource": "{{ $ajax_source }}",
    "aoColumns": [
    @foreach ($columns as $index => $column)
      { "mData": "{{ $column }}" }@if ($index+1 != count($columns)), @endif

    @endforeach],
    "fnInitComplete": function(oSettings, json) {
      $(this).parents ('.dataTables_wrapper').find ('.dataTables_filter input').prop ('placeholder', 'Search...').addClass ('form-control input-sm')
    }
  })
})