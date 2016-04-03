<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $title or "Modpack Index - An Index of Minecraft Modpacks" }}</title>
    <meta name="description" content="{{ $meta_description or "Discover the perfect Minecraft modpack. Over 100 modpacks, including packs from Feed the Beast, Curse, ATLauncher, and Technic Platform." }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="UU0yBqhJnqJWN02U6v4-eRlPBGbj0ep8ZRAK_gotswM"/>
    <meta name="msvalidate.01" content="B61C8791210048221466BFB3DA2C9294"/>

    <!-- Google Font: Open Sans -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oswald:400,300,700">

    <link rel="stylesheet" href="{{ asset('static/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/mvpready-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/mvpready-flat.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/chosen.min.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/site.css') }}">

    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class=" ">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->

@include('layouts.header')

@yield('content')

@include('layouts.footer')

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<script src="{{ asset('/static/js/mvpready-core.js') }}"></script>
<script src="{{ asset('/static/js/mvpready-admin.js') }}"></script>

@if (isset($sticky_tabs))
    <script src="{{ asset('/static/js/tab-control.js') }}"></script>
@endif

@if (isset($table_javascript))
    <script src="{{ asset('/static/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/static/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

    @if (isset($table_fixed_header))
        <script src="{{ asset('/static/js/plugins/dataTables/extensions/fixedHeader/dataTables.fixedHeader.min.js') }}"></script>
    @endif

    @if (is_array($table_javascript))
        @foreach ($table_javascript as $javascript)
            <script src="{{ $javascript }}"></script>
        @endforeach
    @else
        <script src="{{ $table_javascript }}"></script>
    @endif
@endif

@if (isset($search_javascript))
    <script src="{{ asset('/static/js/pack-finder-select.js') }}"></script>
@endif

@if (isset($chosen))
    <script src="{{ asset('/static/js/plugins/chosen/chosen.jquery.min.js') }}"></script>

    <script type="text/javascript">
        var chosen_config = {
            'placeholder_text_multiple': ' ',
            'search_contains': true
        };
        $(".chosen-select").chosen(chosen_config)
    </script>
@endif

@if (isset($datepicker))
    <script src="{{ asset('/static/js/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $('#datepicker').find('input').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
    </script>
@endif

@if (isset($alert_enabled))
    <script src="{{ asset('/static/js/alert.js') }}"></script>
@endif

<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-7061726-16', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
