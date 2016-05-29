@extends('layouts.app')

@section('content')
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
  <div class="x_panel">
    <div class="x_title">
      <h2>Create Message</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <br>
      @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
      @endif
        {!! Form::model($message, [
            'method' => 'PATCH',
            'route' => ['gcm.update', $message->id],
            'class' => 'form-horizontal form-label-left'
        ]) !!}
        <div class="form-group">
            <div class="control-label col-md-3 col-sm-3 col-xs-12">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <img id="icon_preview" src="" width="52px" height="52px"/>
            </div>
        </div>
        <div class="form-group">
        {!! Form::label('icon', 'Icon:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('icon', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'required' => 'required']) !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('title', 'Title:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('title', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'required' => 'required']) !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('package_name', 'Package Name:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('package_name', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl']) !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('content', 'Title:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::textarea('content', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'required' => 'required', 'style' => 'height:60px']) !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('type', 'Type:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="switch">
              {{ Form::radio('type', false, $message->type , ['class' => 'switch-input', 'id' => 'direct']) }}
              <label for="direct" class="switch-label switch-label-off">Direct URL </label>
              {{ Form::radio('type', true, !$message->type, ['class' => 'switch-input', 'id' => 'market']) }}
              <label for="market" class="switch-label switch-label-on">Package Name</label>
              <span class="switch-selection"></span>
            </div>
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('direct_url', 'Direct Url:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::textarea('direct_url', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'style' => 'height:60px']) !!}
          </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
            <a href="/gcm" class="btn btn-primary">Back</a>
          </div>
        </div>

        {!! Form::close() !!}
    </div>
  </div>
</div>
<script type="text/javascript">
    var icon_preview = document.getElementById("icon_preview");
    var icon = document.getElementById("icon");
    icon.onchange=function(){
        if (icon.value != '')
        {
            icon_preview.src = icon.value;
        }   
    }
</script>
@endsection