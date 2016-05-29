<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>FzenStudio! | </title>

  <!-- Bootstrap core CSS -->

  <link href="/css/bootstrap.min.css" rel="stylesheet">

  <link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="/css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="/css/custom.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/css/maps/jquery-jvectormap-2.0.3.css" />
  <link href="/css/icheck/flat/green.css" rel="stylesheet" />
  <link href="/css/floatexamples.css" rel="stylesheet" type="text/css" />

  <script src="/js/jquery.min.js"></script>
  <script src="/js/nprogress.js"></script>

  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>
<body class="nav-md">

  <div class="container body">


    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
            <a href="/" class="site_title"><i class="fa fa-paw"></i> <span>FzenStudio!</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2>{{ Auth::user()->name }}</h2>
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
                <li><a href="/"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                </li>
                <li><a href="/gcm"><i class="fa fa-edit"></i> GCM <span class="fa fa-chevron-down"></span></a>
                </li>
                <li><a href="/app"><i class="fa fa-desktop"></i> Applications <span class="fa fa-chevron-down"></span></a>
                </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div>
          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img src="images/img.jpg" alt="">{{ Auth::user()->name }}
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                  <li><a href="/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>

      </div>
      <!-- /top navigation -->
      <div class="right_col" role="main">
    @if(Session::has('flash_message'))
    <div class="row">
        <div class="alert alert-success col-md-6 col-md-offset-3">
            {{Session::get('flash_message')}}
        </div>
    </div>
    @endif
    @yield('content')
      <!-- /page content -->
      </div>
    </div>

  </div>

  <script src="/js/bootstrap.min.js"></script>

  <!-- icheck -->
  <script src="/js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="/js/moment/moment.min.js"></script>
  <script type="text/javascript" src="/js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="/js/chartjs/chart.min.js"></script>

  <script src="/js/custom.js"></script>

  <!-- flot js -->
  <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
  <script type="text/javascript" src="/js/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="/js/flot/jquery.flot.pie.js"></script>
  <script type="text/javascript" src="/js/flot/jquery.flot.orderBars.js"></script>
  <script type="text/javascript" src="/js/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="/js/flot/date.js"></script>
  <script type="text/javascript" src="/js/flot/jquery.flot.spline.js"></script>
  <script type="text/javascript" src="/js/flot/jquery.flot.stack.js"></script>
  <script type="text/javascript" src="/js/flot/curvedLines.js"></script>
  <script type="text/javascript" src="/js/flot/jquery.flot.resize.js"></script>
  <!-- /footer content -->
    {{-- <script src="{{ elixir('/js/app.js') }}"></script> --}}
</body>
</html>
