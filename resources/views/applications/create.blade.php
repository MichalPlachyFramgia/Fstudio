@extends('layouts.app')

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Create New App </h2>
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
      {!! Form::open(array('route' => 'app.store', 'files' => true, 'class' => 'form-horizontal form-label-left')) !!}
        <div class="form-group">
            <div class="control-label col-md-3 col-sm-3 col-xs-12">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <img id="icon_preview" src="" width="52px" height="52px"/>
            </div>
        </div>
        <div class="form-group">
        {!! Form::label('icon', 'Choose an image', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::file('icon') !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('name', 'Name:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('name', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'required' => 'required']) !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('package_name', 'Package Name:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('package_name', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'required' => 'required']) !!}
          </div>
        </div>
        <div class="form-group">
        {!! Form::label('application_id', 'Application id:', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) !!}
          <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::text('application_id', null, ['class' => 'form-control col-md-7 col-xs-12 parsley-errorl', 'required' => 'required']) !!}
          </div>
        </div>
        
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          {!! Form::submit('Create New App', ['class' => 'btn btn-success']) !!}
            <a href="/app" class="btn btn-primary">Back</a>
          </div>
        </div>

        {!! Form::close() !!}
    </div>
  </div>
</div>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#icon_preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#icon").change(function(){
        readURL(this);
    });
</script>
@endsection