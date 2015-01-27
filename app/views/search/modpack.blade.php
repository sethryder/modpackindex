@extends('layouts.base')

@section('content')
<div class="content">

        <div class="container">

          <div class="portlet">

            <h3 class="portlet-title">
             <u>{{ $version }} Modpack Finder</u>

            </h3>

            <div class="portlet-body">
                <p>Find the right Modpack for you. You can search by Tags/Type, Mods, or both!</p>
            </div>

            <p>&nbsp;</p>

          <h4 class="portlet-title">
              <u>What are you looking for?</u>
          </h4>

          <div class="portlet-body" style="width: 50%">
              <div class="form-group">
                  {{ Form::open(array('url' => '/modpack/finder/'. $url_version, 'class' => 'form parsley-form')) }}
                  {{ Form::label('tags','Tags / Type') }}:
                  @if (isset($results))
                      {{ Form::select('tags[]', $tags, $selected_tags, array('multiple', 'placeholder' => 'Start typing!', 'class' => 'chosen-select form-control')) }}
                  @else
                      {{ Form::select('tags[]', $tags, null, array('multiple', 'placeholder' => 'Start typing!', 'class' => 'chosen-select form-control')) }}
                  @endif
              </div>

              <div class="form-group">
                {{ Form::label('mods','Mods') }}:
                @if (isset($results))
                    {{ Form::select('mods[]', $mods, $selected_mods, array('multiple', 'placeholder' => 'Start typing!', 'class' => 'chosen-select form-control')) }}
                @else
                    {{ Form::select('mods[]', $mods, null, array('multiple', 'placeholder' => 'Start typing!', 'class' => 'chosen-select form-control')) }}
                @endif
              </div>
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
                      <th style="width: 36%">Description</th>
                      <th style="width: 15%">Creators(s)</th>
                      <th style="width: 1%"></th>
                      <th style="width: 18%">Link(s)</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>MC Version</th>
                      <th>Description</th>
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