@extends('layouts.base')

@section('content')
        <div class="content">

        <div class="container">

          <div class="portlet">

            <h3 class="portlet-title">
              <u>{{ $mod->name }}</u>

            </h3>

            <div style="position: relative; bottom: 15px;"><h5>by
                <?php $i=0 ?>
                @foreach($authors as $index => $author)
                    {{$author->name}}@if ($i+1 != count($authors)), @endif
                    <?php $i++; ?>
                @endforeach
            </h5>
            <p>
            <?php $i=0 ?>
            @foreach ($links as $index => $link)
                @if ($index == 'website')
                    <a href="{{ $link }}"><i class="fa fa-external-link"></i> Website</a>
                @endif
                @if ($index == 'download_link')
                    <a href="{{ $link }}"><i class="fa fa-download"></i> Download</a>
                @endif
                @if ($index == 'donate_link')
                    <a href="{{ $link }}"><i class="fa fa-dollar"></i> Donate</a>
                @endif
                @if ($index == 'wiki_link')
                    <a href="{{ $link }}"><i class="fa fa-book"></i> Wiki</a>
                @endif
                @if ($i+1 != count($links))| @endif
                <?php $i++; ?>
            @endforeach
                @if (isset($user_permissions['mod_edit']))
                    | <a href="/mod/edit/{{ $mod->id }}"><i class="fa fa-edit"></i> Edit</a>
                @endif
            </p>
            <div class="portlet-body"></div>
                @if ($mod->description == '')
                    <p>{{ $mod->deck }}</p>
                @else
                    <p>{{ $mod->description }}</p>
                @endif
            <p>&nbsp;</p>

            <ul id="modpack_page" class="nav nav-tabs nav-tabs-sticky">
                <li class="active">
                    <a href="#modpack_list" data-toggle="tab">Modpack List</a>
                </li>

                @if (isset($youtube_videos[0]))
                    <li class="">
                        <a href="#youtube" data-toggle="tab">Spotlights</a>
                    </li>
                @endif
            </ul>

            <div id="modpack_page" class="tab-content">

                <div class="tab-pane fade active in" id="modpack_list">

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

                </div>
            </div>

          </div> <!-- /.portlet -->

        </div> <!-- /.container -->

      </div> <!-- .content -->
    </div>
@stop