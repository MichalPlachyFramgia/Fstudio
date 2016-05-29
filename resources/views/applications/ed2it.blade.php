@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel">
            <h1>Edit App </h1>
<p class="lead">Edit App below. <a href="{{ route('app.index') }}">Go back to all apps.</a></p>
<hr>
            @if($errors->any())
			    <div class="alert alert-danger">
			        @foreach($errors->all() as $error)
			            <p>{{ $error }}</p>
			        @endforeach
			    </div>
			@endif
{!! Form::model($app, [
    'method' => 'PATCH',
    'route' => ['app.update', $app->id],
    'files' => true,
]) !!}

<div class="form-group">
<img id="icon_preview" src="{{$app->icon}}" width="52px" height="52px" />
                <br>
                <div class="form-group">
    {!! Form::label('icon', 'Choose an image') !!}
    {!! Form::file('icon') !!}
                </div>
                </div>

                <div class="form-group">
    {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
    {!! Form::label('package_name', 'Package name:', ['class' => 'control-label']) !!}
    {!! Form::text('package_name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
    {!! Form::label('application_id', 'Application Id:', ['class' => 'control-label']) !!}
    {!! Form::text('application_id', null, ['class' => 'form-control']) !!}
                </div>

{!! Form::submit('Update Task', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
			</div>
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