@extends('layouts.base')

@section('content')
        <div class="content">

        <div class="container">

          <div class="portlet">

            <h3 class="portlet-title">
             <u>{{ $modpack->name }} ({{ $modpack->version->name  }})</u>

            </h3>

            <div style="position: relative; bottom: 15px;"><h5>by
                <?php $i=0 ?>
                @foreach($creators as $index => $creator)
                    {{$creator->name}}@if ($i+1 != count($creators)), @endif
                    <?php $i++; ?>
                @endforeach
            </h5>
            <p>
                <a href="/launcher/{{ $launcher->slug }}"><i class="fa fa-rocket"></i> {{ $launcher->name }}</a> |
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
                @if (isset($user_permissions['modpack_edit']))
                    | <a href="/modpack/edit/{{ $modpack->id }}"><i class="fa fa-edit"></i> Edit</a>
                @endif
            </p>

            @if (count($tags) > 0)
                <p><?php $i=0 ?>
                <i class="fa fa-tags"></i> Tags:
                @foreach ($tags as $tag)
                    <a href="/modpack/finder/?tag={{ $tag->slug }}">{{ $tag->name }}</a>@if ($i+1 != count($tags)),@endif
                    <?php $i++; ?>
                @endforeach</p>
            @endif


            @if (isset($pack_code))
                <p>
                @if ($launcher->slug == 'feed-the-beast')
                    Pack Code: <b>{{ $pack_code->code }}</b> (<a href="/about/modpack-codes">What's this?</a>)
                @endif
                @if ($launcher->slug == 'atlauncher')
                    Pack Code: <b>{{ $pack_code->code }}</b> (<a href="/about/modpack-codes">What's this?</a>)
                @endif
                @if ($launcher->slug == 'technic-platform')
                    Pack URL: <input type="text" name="url" value="{{ $pack_code->code }}"> (<a href="/about/modpack-codes">What's this?</a>)
                @endif
                @if (isset($user_permissions['modpack_code_edit']))
                    <a href="/modpack-code/edit/{{ $pack_code->id }}"><i class="fa fa-edit"></i> Edit</a>
                @endif
                </p>
            @endif

            <div class="portlet-body"></div>

                @if ($modpack->description == '')
                    <p>{{ $modpack->deck }}</p>
                @else
                    <p>{{ $modpack->description }}</p>
                @endif

            <p>&nbsp;</p>
            <h4 class="portlet-title">
                <u>Mod List</u>
            </h4>

            <div class="portlet-body">
              <table class="table table-striped table-bordered" id="table-1">
                <thead>
                  <tr>
                    <th style="width: 20%">Name</th>
                    <th style="width: 12%">MC Version(s)</th>
                    <th style="width: 35%">Description</th>
                    <th style="width: 15%">Author(s)</th>
                    <th style="width: 18%">Link(s)</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>MC Version(s)</th>
                    <th>Description</th>
                    <th>Author(s)</th>
                    <th>Link(s)</th>
                  </tr>
                </tfoot>
              </table>

            </div> <!-- /.portlet-body -->

          </div> <!-- /.portlet -->

        </div> <!-- /.container -->

      </div> <!-- .content -->
</div>
@stop