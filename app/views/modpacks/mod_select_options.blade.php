@foreach ($mods as $k => $mod_array)
    var {{ $k }}_options = [
    <?php $i = 0; ?>
    @foreach($mod_array as $id => $mod)
        {text: "{{ $mod }}", value: "{{ $id }}"}@if ($i+1 != count($mod_array)), @endif

        <?php $i++; ?>
    @endforeach ];
@endforeach


$(function(){

$('.check').change(function(){
var data= $(this).val();
$("#mods").replaceOptions(options);
});

$('.check')
.trigger('change');
});