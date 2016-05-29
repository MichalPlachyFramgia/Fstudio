@extends('layouts.app')

@section('content')
<style type="text/css">
    .checkbox-grid li {
    display: block;
    float: left;
    width: 350px;
    margin-bottom: 10px;
    padding-bottom: 10px;
    height: 50px;
}

/* .modal-fullscreen */

.modal-fullscreen {
  background: transparent;
}
.modal-fullscreen .modal-content {
  background: transparent;
  border: 0;
  -webkit-box-shadow: none;
  box-shadow: none;
}
.modal-backdrop.modal-backdrop-fullscreen {
  background: #ffffff;
}
.modal-backdrop.modal-backdrop-fullscreen.in {
  opacity: .97;
  filter: alpha(opacity=97);
}

/* .modal-fullscreen size: we use Bootstrap media query breakpoints */

.modal-fullscreen .modal-dialog {
  margin: 0;
  margin-right: auto;
  margin-left: auto;
  width: 100%;
}
@media (min-width: 768px) {
  .modal-fullscreen .modal-dialog {
    width: 750px;
  }
}
@media (min-width: 992px) {
  .modal-fullscreen .modal-dialog {
    width: 970px;
  }
}
@media (min-width: 1200px) {
  .modal-fullscreen .modal-dialog {
     width: 1170px;
  }
}

/* centering styles for jsbin */
html,
body {
  width:100%;
  height:100%;
}
html {
  display:table;
}
body {
  display:table-cell;
  vertical-align:middle;
}
body > .btn {
  display: block;
  margin: 0 auto;
}
</style>
<div class="container">
	<h3>Google Cloud Message</h3>
	<a href="{{ route('gcm.create') }}" class="btn btn-primary">Add New Gcm</a>


    <!-- Trigger the modal with a button -->

    <!-- Modal -->
    <div class="modal modal-fullscreen fade" id="modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        {!! Form::open(array('route' => array('gcm.send'))) !!}
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4>
          </div>
          <div class="modal-body">
            <div class="frmSearch">
                {{ Form::hidden('message_id', '0', array('class' => 'message_id')) }}
                <input type="text" id="search-box" placeholder="Country Name" />
                <div id="suggesstion-box"></div>
            </div>
            <ul class="checkbox-grid">
                <li>
                    {!! Form::checkbox('applications[0]', $i=0, null, ['id' => 'checkAll']) !!}
                    {!! Form::label('applications', 'ALL') !!}
                </li>
            @foreach($applications as $application)
                <li>
                    {!! Form::checkbox('applications['.++$i.']', $application->id, null, ['class' => 'app']) !!}
                    {!! Html::image('application_icon/'.$application->icon, '', array( 'width' => 30, 'height' => 30 )) !!}
                    {!! Form::label('applications', $application->name) !!}
                </li>
            @endforeach
            </ul>
          </div>
          <div class="modal-footer">
            {!! Form::submit('SEND', ['class' => 'btn btn-primary']) !!}
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>              
	<hr>

	<table class="table table-bordered">
	<tr>
		<td>#</td>
    	<td>Icon</td>
    	<td>Title</td>
    	<td>Content</td>
    	<td>Packet name</td>
        <td>Success/All</td>
        <td>Last Time</td>
        <td>Send History</td>
        <td>Edit</td>
        <td>Send</td>
        <td>Delete</td>
	</tr>
	<?php $i = 1; ?>
@foreach($messages as $message)
	<tr>
		<td>{{$i++}}</td>
    	<td><a href="https://play.google.com/store/apps/details?id={{ $message->package_name }}"><img src="{{ $message->icon }}" width="50px" height="50px"></a></td>
    	<td>{{ $message->title }}</td>
    	<td>{{ $message->content }}</td>
    	<td>{{ $message->package_name }}</td>
        <td>{{ $message->success }}/{{ $message->success + $message->failed }}</td>
        <td>{{ date("d-m-Y h:i:s a",strtotime($message->send_at)+3600*7) }}</td>
        <td><a href="{{ route('gcm.message_histories', $message->id) }}" class="btn btn-primary">Show History</a></td>
        <td><a href="{{ route('gcm.edit', $message->id) }}" class="btn btn-primary">Edit</a></td>
        <!-- <td><a href="{{ route('gcm.send', $message->id) }}" class="btn btn-primary">Send</a></td> -->
        <td>
            <button type="button" class="modal-click btn btn-primary" data-toggle="modal" data-target="#modal-fullscreen" id="{{$message->id}}">
                Send
            </button>
        </td>
        <td>
        	{!! Form::open([
	            'method' => 'DELETE',
	            'route' => ['gcm.destroy', $message->id]
	        ]) !!}
	            {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure ?");']) !!}
	        {!! Form::close() !!}
        </td>
	</tr>
@endforeach
    </table>
	{!! $messages->links() !!}
</div>
<script type="text/javascript">
    // AJAX call for autocomplete 
$(document).ready(function(){
    $("#search-box").keyup(function(){
        $.ajax({
        type: "POST",
        url: "readCountry.php",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#search-box").css("background","#FFF");
        }
        });
    });
});
//To select country name
function selectCountry(val) {
$("#search-box").val(val);
$("#suggesstion-box").hide();
}

// .modal-backdrop classes
$(".modal-fullscreen").on('show.bs.modal', function () {
  setTimeout( function() {
    $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
  }, 0);
});
$(".modal-fullscreen").on('hidden.bs.modal', function () {
  $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
});

$(document).ready(function() {
    $(".modal-click").click(function(event) {
        $(".message_id").val(event.target.id);
    });
});
$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});

$("input:checkbox").change(function () {
    $("#checkAll").prop('checked', $(this).prop("checked"));
});

$(".app").change(function(){
    if ($('.app:checked').length == $('.app').length) {
       $('#checkAll').attr('checked', true);
    }else{
		$('#checkAll').attr('checked', false);
	}
});
</script>
@endsection