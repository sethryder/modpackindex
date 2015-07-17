@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>{{{ $launcher->name }}} (@if ($version == 'All')
                            All Minecraft Versions
                        @else
                            {{{ $version }}}
                        @endif)</u>
                </h3>

                <p>
                    <?php $i = 0 ?>
                    @foreach ($links as $index => $link)
                        @if ($index == 'website')
                            <a href="{{{ $link }}}"><i class="fa fa-external-link"></i> Website</a>
                        @endif
                        @if ($index == 'download_link')
                            <a href="{{{ $link }}}"><i class="fa fa-download"></i> Download</a>
                        @endif
                        @if ($index == 'donate_link')
                            <a href="{{{ $link }}}"><i class="fa fa-dollar"></i> Donate</a>
                        @endif
                        @if ($index == 'wiki_link')
                            <a href="{{{ $link }}}"><i class="fa fa-book"></i> Wiki</a>
                        @endif
                        @if ($i+1 != count($links))| @endif
                        <?php $i++; ?>
                    @endforeach
                </p>

                <div class="portlet-body"></div>

                <p>{{{ $launcher->description }}}</p>

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
                            <th>Links(s)</th>

                        </tr>
                        </tfoot>
                    </table>

                </div>
                <!-- /.portlet-body -->

            </div>
            <!-- /.portlet -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop