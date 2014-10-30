@extends('layouts.base')

@section('content')
<div class="content">

        <div class="container">

          <div class="portlet">

            <h3 class="portlet-title">
             <u>{{ $version }} Modpack Finder</u>

            </h3>

            <div class="portlet-body">
                <p>Modpack Finder is here to help you find the right modpack for you! Just feed it a list of Mods you
                want to play and we will do our best to find you the Modpacks that provide them.</p>
            </div>

            <p>&nbsp;</p>

            <h4 class="portlet-title">
                <u>Select Your Mods</u>
            </h4>

            <div class="portlet-body">
            {{ Form::open(array('url' => '/modpack/finder/'. $url_version, 'class' => 'form parsley-form')) }}
                {{ Form::label('mods','Mods') }}:
                @if (isset($results))
                    {{ Form::select('mods[]', $mods, $selected_mods, array('multiple', 'placeholder' => 'Start typing!', 'class' => 'chosen-select form-control')) }}
                @else
                    {{ Form::select('mods[]', $mods, null, array('multiple', 'placeholder' => 'Start typing!', 'class' => 'chosen-select form-control')) }}
                @endif
                <p>&nbsp;</p>
                {{ Form::submit('Search', ['class' => 'btn btn-danger']) }}

            {{ Form::close() }}
            </div>



            @if (isset($results))
            <p>&nbsp;</p>
            <h4 class="portlet-title">
                <u>Results</u>
            </h4>

            <div class="portlet-body">
              <table class="table table-striped table-bordered" id="table-1">
                  <thead>
                    <tr>
                      <th style="width: 20%">Name</th>
                      <th style="width: 10%">MC Version</th>
                      <th style="width: 36%">Deck</th>
                      <th style="width: 15%">Creators(s)</th>
                      <th style="width: 1%"></th>
                      <th style="width: 18%">Link(s)</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>MC Version</th>
                      <th>Deck</th>
                      <th>Creators(s)</th>
                      <th></th>
                      <th>Link(s)</th>

                    </tr>
                  </tfoot>
                </table>

            </div> <!-- /.portlet-body -->
          @endif

          </div> <!-- /.portlet -->

        </div> <!-- /.container -->

      </div> <!-- .content -->
@stop