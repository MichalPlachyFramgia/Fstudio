@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel">
            <h1>Update App Details</h1>
<p class="lead">Update Version App below. <a href="{{ route('app.index') }}">Go back to all App.</a></p>
<hr>
            @if($errors->any())
			    <div class="alert alert-danger">
			        @foreach($errors->all() as $error)
			            <p>{{ $error }}</p>
			        @endforeach
			    </div>
			@endif
{!! Form::model($app, array('route' => ['app.update_version',$app->id])) !!}

				<div class="form-group">
    {!! Form::label('version_code', 'Version Code:', ['class' => 'control-label']) !!}
    {!! Form::text('version_code', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
    {!! Form::label('version_name', 'Version Name:', ['class' => 'control-label']) !!}
    {!! Form::text('version_name', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
    {!! Form::label('update_title', 'Update Title:', ['class' => 'control-label']) !!}
    {!! Form::text('update_title', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
    {!! Form::label('update_message', 'Update Message:', ['class' => 'control-label']) !!}
    {!! Form::text('update_message', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
    {!! Form::label('update_type', 'Update Type:', ['class' => 'control-label']) !!}
    {!! Form::label('update_type', 'Market:', ['class' => 'control-label']) !!}
    {!! Form::radio('update_type', 1, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
    {!! Form::label('update_type', 'Direct:', ['class' => 'control-label']) !!}
    {!! Form::radio('update_type', 0, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
    {!! Form::label('direct_url', 'Direct URL:', ['class' => 'control-label']) !!}
    {!! Form::text('direct_url', null, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
    {!! Form::label('update_package', 'Package name:', ['class' => 'control-label']) !!}
    {!! Form::text('update_package', null, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
    {!! Form::label('force_update', 'Force Update:', ['class' => 'control-label']) !!}
    {!! Form::label('force_update', 'Yes', ['class' => 'control-label']) !!}
    {!! Form::radio('force_update', true, ['class' => 'control-label']) !!}
    {!! Form::label('force_update', 'No', ['class' => 'control-label']) !!}
    {!! Form::radio('force_update', false, ['class' => 'form-control']) !!}
				</div>
    {!! Form::label('status', 'Status:', ['class' => 'control-label']) !!}
    {!! Form::label('status', 'On', ['class' => 'control-label'] ) !!}
    {!! Form::radio('status', true, ['class' => 'control-label'] ) !!}
    {!! Form::label('status', 'Off', ['class' => 'control-label'] ) !!}
    {!! Form::radio('status', false, ['class' => 'control-label'] ) !!}
{!! Form::submit('Update Version', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
