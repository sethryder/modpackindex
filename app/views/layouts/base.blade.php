<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $title or "Modpack Index - An Index of Minecraft Modpacks" }}</title>
    <meta name="description" content="{{{ $meta_description or "Discover the perfect Minecraft modpack. Including modpacks from Feed the Beast, ATLauncher, and Technic Platform." }}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- Google Font: Open Sans -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">

    <link rel="apple-touch-icon icon-precomposed" href="/static/images/apple-touch-icon.png"/>

    <!-- build:css /static/css/minified.css -->
    <link rel="stylesheet" href="/static/css/font-awesome.min.css">
    <link rel="stylesheet" href="/static/css/bootstrap.css">
    <link rel="stylesheet" href="/static/css/mvpready-admin.css">
    <link rel="stylesheet" href="/static/css/mvpready-flat.css">
    <link rel="stylesheet" href="/static/css/chosen.min.css">
    <!-- endbuild -->

    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class=" ">
      <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
      <![endif]-->

      @include('layouts.header')

      @yield('content')

      @include('layouts.footer')

      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

      <script src="/static/js/plugins/flot/jquery.flot.js"></script>
      <script src="/static/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
      <script src="/static/js/plugins/flot/jquery.flot.pie.js"></script>
      <script src="/static/js/plugins/flot/jquery.flot.resize.js"></script>
      <script src="/static/js/plugins/flot/jquery.flot.orderBars.js"></script>
      <script src="/static/js/mvpready-core.js"></script>
      <script src="/static/js/mvpready-admin.js"></script>
      <script src="/static/js/demos/flot/area.js"></script>
      <script src="/static/js/demos/flot/stacked-vertical.js"></script>

      <?php if (isset($table_javascript)) { ?>
      <script src="/static/js/plugins/dataTables/jquery.dataTables.js"></script>
      <script src="/static/js/plugins/dataTables/dataTables.bootstrap.js"></script>
      <script src="{{ $table_javascript }}"></script>
      <?php } ?>

      <?php if(isset($chosen)) { ?>
      <script src="/static/js/plugins/chosen/chosen.jquery.min.js"></script>

      <script type="text/javascript">
          var chosen_config = {
            'placeholder_text_multiple':' ',
            'search_contains': true
          }
          $(".chosen-select").chosen(chosen_config)
      </script>
      <?php } ?>
      <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-7061726-16', 'auto');
          ga('send', 'pageview');

      </script>
  </body>
</html>
