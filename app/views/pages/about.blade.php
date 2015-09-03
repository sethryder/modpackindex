@extends('layouts.base')

@section('content')
    <div class="content">

        <div class="container">

            <div class="portlet">

                <h3 class="portlet-title">
                    <u>About Modpack Index</u>
                </h3>

                <div class="portlet-body">
                    <p><b>Modpack Index</b> was built as utility to help keep track of the amazing growing community of
                        modpacks. The sites true aim is to help you find the right modpack for yourself, that is why we
                        built the ‘<a href="{{ action('SearchController@getModpackSearch') }}">Pack Finder</a>’ feature to help you do so. The
                        site is currently in early beta so it may have some rough edges, but we are continuing to add
                        features and fix bugs.</p>

                    <p>If you would like to hang out or chat visit our IRC channel <a
                                href="https://webchat.esper.net/?channels=ModpackIndex"
                                target="_blank">#ModpackIndex</a> on <a href="https://www.esper.net/" target="_blank">EsperNet</a>.
                    </p>
                </div>
                <p>&nbsp;</p>

                <h3 class="portlet-title">
                    <u>FAQ</u>
                </h3>

                <div class="portlet-body">
                    <h4><u>How do I add my Modpack or Video(s)?</u></h4>

                    <p>Use the the submit menu on the top right of the site. Once we get your submission we will get it
                        added to the site.</p>

                    <p>&nbsp;</p>

                    <h4><u>Why isn't my stream showing up?</u></h4>

                    <p>For your stream to show up on the site it must include the Modpack name in the stream title (or
                        some variation of it). If you think
                        your stream should be showing up and it is not <a href="{{ action('StaticPagesController@getContact') }}">let us know</a>.</p>

                    <p>&nbsp;</p>

                    <h4><u>Hey! This modpack/mod information is incorrect!</u></h4>

                    <p>Sorry about that! The site is currently small and all the data is currently kept up to date
                        manually. <a href="{{ action('StaticPagesController@getContact') }}">Contact</a> us and let us know so we can fix it!

                    <p>We currently exploring the best way to expand the site and allow users to edit mods/modpacks. If
                        you are interested in possibly helping out be sure to <a href="{{ action('StaticPagesController@getContact') }}">contact us</a>!</p>

                    <p>&nbsp;</p>

                    <h4><u>I found a bug or have a great idea for a feature!</u></h4>

                    <p>Be sure to let us know. You can reach us in our <a
                                href="https://webchat.esper.net/?channels=ModpackIndex" target="_blank">IRC channel</a>
                        or contact us <a href="{{ action('StaticPagesController@getContact') }}">here</a>!</p>
                </div>

            </div>
            <!-- /.portlet -->

        </div>
        <!-- /.container -->

    </div> <!-- .content -->
@stop