@extends('layouts.base')

@section('content')
        <div class="content">

        <div class="container">

          <div class="portlet">

            <h3 class="portlet-title">
              <u>All Mods</u>
            </h3>

            <div class="portlet-body">

              <table class="table table-striped table-bordered" id="table-1">
                <thead>
                  <tr>
                    <th style="width: 20%">Name</th>
                    <th style="width: 42%">Deck</th>
                    <th style="width: 18%">MC Version(s)</th>
                    <th style="width: 20%">Author(s)</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>Deck</th>
                    <th>MC Version(s)</th>
                    <th>Author(s)</th>
                  </tr>
                </tfoot>
              </table>

            </div> <!-- /.portlet-body -->

          </div> <!-- /.portlet -->

        </div> <!-- /.container -->

      </div> <!-- .content -->
@stop