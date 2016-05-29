@extends('layouts.app')

@section('content')
<div class="container">
  @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
  @endif
  <div class="row">
    <div class="col-md-6">
      <table class="table table-striped" style="height:100px;width:500px;">
        <tr>
          <td style="width:110px;"><img class="" id="icon_preview" style="width:100px;height:100px;"/></td>
          <td>
            <div class="title" id="title_preview" style="height:30px;"></div>
            <div class="content" id="content_preview" style="height:70px;"></div>
          </td>
        </tr>
      </table>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-2">
          <label>Type message</label>:<br>
          <label>URL</label>:<br>
          <label>Success</label>:<br>
          <label>Failed</label>:<br>
          <label>Countries</label>:<br>
          <label>Application</label>:<br>
        </div>
        <div class="col-md-10">
          <label id="type_message_preview">- </label><br>
          <label id="url_preview">- </label><br>
          <label id="success_preview">- </label><br>
          <label id="failed_preview">- </label><br>
          <a id="countries_button_preview">-Countries List-</a><br>
          <a id="applications_button_preview">-Applications List-</a><br>
        </div>
      </div>
    </div>
  </div>
  <div>
    From <input id="start" type="number" min="0" value="0" /> to 
    <input id="end" type="number" min="1" max="{{$devices_count}}" value="{{$devices_count}}" />
    <input type="text" id="data_textbox"/><br>
    <input type="checkbox" id="application_checkbox"> Apps <input type="checkbox" id="country_checkbox"> Country<br>
    <div>
      <div class="row">
        <div class="col-md-9" id="choosen_box">1</div>
        <div class="col-md-3">
          <div>
            <button type="button" class="btn" data-toggle="modal" data-target="#myModal">Create Message</button>
          </div>
          <div style="overflow:scroll;height:600px;">
            @foreach($messages as $message)
              <div class="row message" id="message_{{$message->id}}">
                <script type="text/javascript">
                  $("#message_{{$message->id}}").click(function(){
                    var icon_preview = document.getElementById("icon_preview");
                    icon_preview.src = "{{$message->icon}}";
                    $('#title_preview').text("{!! $message->title !!}");
                    $('#content_preview').text("{!! $message->content !!}");
                    if ("{!! $message->type !!}") {
                      $('#type_message_preview').text("Google");
                      $('#url_preview').text("{!! $message->package_name !!}");
                    }else{
                      $('#type_message_preview').text("Direct URL");
                      $('#url_preview').text("{!! $message->direct_url !!}");
                    }
                  });
                </script>
                <div class="col-md-2">
                  <img style="width:50px;height:50px;" src="{{$message->icon}}"/>
                </div>
                <div class="col-md-10">
                  <b>{!! $message->title !!}</b><br>
                  {!! $message->content !!}<br>
                </div>
              </div>
              <hr>
            @endforeach
          </div>
        </div>
      </div>
      <button class="btn btn-default" id="send_btn">Send Message</button>
    </div>
  </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
  <style>
.switch {
  position: relative;
  height: 33px;
  max-width: 220px;
  background: #EEEEEE;
  border-radius: 3px;
}

.switch-label {
  position: relative;
  z-index: 2;
  float: left;
  width: 108px;
  line-height: 33px;
  font-size: 13px;
  text-align: center;
  cursor: pointer;
}
.switch-label:active {
  font-weight: bold;
  color: #000000;
}

.switch-label-off {
  padding-left: 2px;
}

.switch-label-on {
  padding-right: 2px;
}


.switch-input {
  display: none;
}
.switch-input:checked + .switch-label {
  font-weight: bold;
  color: #FFFFFF;
  text-shadow: 0 1px rgba(255, 255, 255, 0.25);
  -webkit-transition: 0.15s ease-out;
  -moz-transition: 0.15s ease-out;
  -ms-transition: 0.15s ease-out;
  -o-transition: 0.15s ease-out;
  transition: 0.15s ease-out;
  -webkit-transition-property: color, text-shadow;
  -moz-transition-property: color, text-shadow;
  -ms-transition-property: color, text-shadow;
  -o-transition-property: color, text-shadow;
  transition-property: color, text-shadow;
}
.switch-input:checked + .switch-label-on ~ .switch-selection {
  left: 50%;
  /* Note: left: 50%; doesn't transition in WebKit */
}

.message { 
  position: relative;
  padding-bottom: 50px;
}

.switch-selection {
  position: absolute;
  z-index: 1;
  top: 2px;
  left: 2px;
  display: block;
  width: 108px;
  height: 29px;
  border-radius: 3px;
  background-color: #1479B8;
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #9dd993), color-stop(100%, #65bd63));
  background-image: -webkit-linear-gradient(top, #1479B8, #1479B8);
  background-image: -moz-linear-gradient(top, #1479B8, #1479B8);
  background-image: -ms-linear-gradient(top, #1479B8, #1479B8);
  background-image: -o-linear-gradient(top, #1479B8, #1479B8);
  background-image: linear-gradient(top, #1479B8, #1479B8);
  -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.5), 0 0 2px rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px rgba(255, 255, 255, 0.5), 0 0 2px rgba(0, 0, 0, 0.2);
  -webkit-transition: left 0.15s ease-out;
  -moz-transition: left 0.15s ease-out;
  -ms-transition: left 0.15s ease-out;
  -o-transition: left 0.15s ease-out;
  transition: left 0.15s ease-out;
}
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="form-horizontal form-label-left">
  <div class="x_panel">
    <div class="x_title">
      <h2>Create Message</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <br>
        <div class="form-group">
            <div class="control-label col-md-3 col-sm-3 col-xs-12">
            </div>
         </div>
        <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Icon:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input class="form-control col-md-7 col-xs-12 parsley-errorl" type="text" id="icon">
          </div>
        </div>
        <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Title:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input class="form-control col-md-7 col-xs-12 parsley-errorl" type="text" id="title">
          </div>
        </div>
        <div class="form-group">
        <label for="package_name" class="control-label col-md-3 col-sm-3 col-xs-12">Package Name:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input class="form-control col-md-7 col-xs-12 parsley-errorl" name="package_name" type="text" id="package_name">
          </div>
        </div>
        <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Content:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control col-md-7 col-xs-12 parsley-errorl" style="height:60px" cols="50" rows="10" id="content"></textarea>
          </div>
        </div>
        <div class="form-group">
        <label for="type" class="control-label col-md-3 col-sm-3 col-xs-12">Type:</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="switch">
              <input class="switch-input" id="direct" name="type" type="radio" value="">
              <label for="direct" class="switch-label switch-label-off">Direct URL </label>
              <input class="switch-input" id="market" checked="checked" name="type" type="radio" value="1">
              <label for="market" class="switch-label switch-label-on">Package Name</label>
              <span class="switch-selection"></span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Direct Url:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea class="form-control col-md-7 col-xs-12 parsley-errorl" style="height:60px" id="direct_url" name="direct_url" cols="50" rows="10"></textarea>
          </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div id="update_preview" class="btn btn-default">Update</div>
            <button type="reset" value="Reset"  class="btn btn-default">Reset</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

$("#update_preview").click(function(){
  var icon_preview = document.getElementById("icon_preview");
  var icon = document.getElementById("icon");
  icon_preview.src = icon.value;
  var title = document.getElementById("title");
  $('#title_preview').text(title.value);
  var content = document.getElementById("content");
  $('#content_preview').text(content.value);
  if ($("#market").prop("checked")) {
    $('#type_message_preview').text("Google");
    var package_name = document.getElementById("package_name");
    $('#url_preview').text(package_name.value);
  }else if ($("#direct").prop("checked")) {
    $('#type_message_preview').text("Direct URL");
    var direct_url = document.getElementById("direct_url");
            $('#url_preview').text(direct_url.value);
  }
});

$('#country_checkbox').change(function () {
    ajax_call(false);
 });
$('#application_checkbox').change(function () {
    ajax_call(false);
 });
jQuery('#data_textbox').on('input propertychange paste', function() {
    ajax_call(false);
});

$('#send_btn').click(function(){
  var start = $('#start').val();
  var end = $('#end').val();
  setTimeout(function() {
    send(start, end, 0, 0);
  }, 2000);
})
function getDataCheckbox(name){
  var arr = [];
  $("input:checkbox[name="+name+"]:checked").each(function(){
    arr.push($(this).val());
  });

  return arr;
}

function send(start, end, message_id, message_history_id){
  var countries_list = getDataCheckbox('country');
  var app_list = getDataCheckbox('application');
  var icon = $('#icon_preview').attr('src');
  if (!icon)
    icon = "";
  var title = $('#title_preview').text();
  var content = $('#content_preview').text();
  var type = $('#type_message_preview').text();
  var url = $('#url_preview').text();

  $.ajax({
      url: "/send_message",
      type: 'POST',
      cache: false,
      data: {
        message_id: message_id,
        message_history_id: message_history_id,
        start: start,
        end: end,
        country: countries_list,
        application: app_list,
        icon: icon,
        title: title,
        content: content,
        type: type,
        url: url
      },
      success: function(response){
        var json_obj = $.parseJSON(response);
        if (json_obj[0] != 0)
        {
          setTimeout(function() {
            send(start + json_obj[1] + json_obj[2], end, json_obj[3], json_obj[4]);
            // send(start + json_obj[1], end - json_obj[2], json_obj[3], json_obj[4]);
          }, 2000);
        }
        else
          alert('done');
        $('#success_preview').text(json_obj[1] + $('#success_preview').val());
        $('#failed_preview').text(json_obj[2] + $('#failed_preview').val());
      },
      error: function (){
        alert('Có lỗi xảy ra');
      }
  });
}
function ajax_call (max_value){
  var countries_list = getDataCheckbox('country');
  var app_list = getDataCheckbox('application');
  var country = $("#country_checkbox").is(':checked');
  var application = $("#application_checkbox").is(':checked');
  var search_data = $("#data_textbox").val();
  $.ajax({
      url: "/search",
      type: 'POST',
      cache: false,
      data: {
        country: country,
        application: application,
        search_data: search_data,
        countries_list: countries_list,
        app_list: app_list
      },
      success: function(response){
        var json_obj = $.parseJSON(response);
        if (max_value != true){
          $("#choosen_box").html(json_obj[0]);
        }else{
          $("#end").attr('max', json_obj[1]);
          $("#end").attr('value', json_obj[1]);
        }
      },
      error: function (){
        alert('Có lỗi xảy ra');
      }
  });
}
</script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection