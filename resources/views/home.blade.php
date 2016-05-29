@extends('layouts.app')

@section('content')
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <link href="css/icheck/flat/green.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<div class="row tile_count">
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> Total Devices</span>
              <div class="count">{!! $devicesCount !!}</div>
              <span class="count_bottom"><i class="green">{!! $newDevice !!}</i> From last Day</span>
            </div>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-clock-o"></i> Total Users</span>
              <div class="count">{!! $users_count !!}</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>{!! $newUser !!}</i> From last Day</span>
            </div>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> Total Apps</span>
              <div class="count green">{!! $appCount !!}</div>
            </div>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> Total Messages</span>
              <div class="count">{!! $messageCount !!}</div>
            </div>
          </div>

        </div>

        <div class="x_panel">
          <div class="x_title">
            <h2>Graph area <small>Sessions</small></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content2">
            <div id="graph_area" style="width:100%; height:300px;"></div>
          </div>
        </div>
        <div style="width:900px;background-color:white;padding:20px;">
          <h3>Applications</h3>
  <table class="table table-bordered">
  <tr>
    <td>#</td>
      <td id="applications">Name</td>
      <td>Users</td>
      <td>Devices</td>
      <td>LauchGame </td>
      <td>Trending 30 days</td>
      <td>More</td>
  </tr>
  <?php $i = 1; ?>
@foreach($apps as $app)
  <tr>
    <td>{{$i++}}</td>
      <td><a href="https://play.google.com/store/apps/details?id={{ $app->package_name }}"><img src="application_icon/{{ $app->icon }}" width="50px" height="50px"></a> {{ $app->name }}</td>
      <td>{{ $app->installed }}</td>
        <td>{{ $app->total_devices }}</td>
        <td>{{ $app->total_devices }}</td>
        <td>
          <span id="app_id_{{$app->id}}" class="sparkline_line" style="height: 160px;">
            <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
          </span>
          <script type="text/javascript">
            function requestDataApp(days){
              $.ajax({
                type: "GET",
                url: "{{url('apps/charts')}}", // This is the URL to the API
                data: { days: days, app_id: {{$app->id}}  }
              })
              .done(function( data ) {
                $("#app_id_{{$app->id}}").sparkline(data, {
                  type: 'line',
                  lineColor: '#26B99A',
                  fillColor: '#ffffff',
                  width: 85,
                  barWidth: 200,
                  spotColor: '#34495E',
                  minSpotColor: '#34495E'
                });
              })
              .fail(function() {
                // alert( "error occured" );
              });
            }
            requestDataApp(50);
          </script>
        </td>
        <td>
        </td>
  </tr>
@endforeach
    </table>
    @if ($pagi != false)
    {!! $pagi->links() !!}
    @else
    <?php
      $page = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['REQUEST_URI'])-1, 1)-1;
      $link = substr($_SERVER['REQUEST_URI'], 0, -1);
      $url = $link.$page;
    ?>
    <ul class="pager"><li><a href="{{ $url }}" rel="prev">«</a></li>
    </ul>
    @endif
    <script>
    if ($(window).width() < 500)
      $("#applications").width(44);
</script>
        </div>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="/js/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="/js/icheck/icheck.min.js"></script>
  <script src="/js/sparkline/jquery.sparkline.min.js"></script>
  <script src="/js/custom.js"></script>
  <!-- pace -->
  <script src="/js/pace/pace.min.js"></script>

  <!-- moris js -->
  <script src="/js/moris/raphael-min.js"></script>
  <script src="/js/moris/morris.min.js"></script>
  <script>
  var chart = Morris.Area({
      element: 'graph_area',
      xkey: 'daytime',
      data: [],
      ykeys: ['users', 'devicecount'],
      lineColors: ['#26B99A', '#34495E', '#5A738E'],
      labels: ['Users', 'Devices'],
      pointSize: 2,
      hideHover: 'auto'
  });
  // Create a function that will handle AJAX requests
  function requestData(days, chart){
    $.ajax({
      type: "GET",
      url: "{{url('users/charts')}}", // This is the URL to the API
      data: { days: days }
    })
    .done(function( data ) {
      chart.setData(JSON.parse(data));
      setTimeout(function(){requestData(30, chart);}, 30000);
    })
    .fail(function() {
      // alert( "error occured" );
    });
  }

  requestData(30, chart);
</script>
@endsection
