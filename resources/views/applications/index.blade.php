@extends('layouts.app')

@section('content')
<div class="container">
	<h3>Applications</h3>
	<a href="{{ route('app.create') }}" class="btn btn-primary">Add New App</a>
	<hr>
	<table class="table table-bordered">
	<tr>
		<td>#</td>
    	<td>Icon</td>
    	<td>Name</td>
    	<td>Packet name</td>
        <td>Application Id</td>
        <td>Installed </td>
        <td>Edit</td>
        <td>Update Version</td>
        <td>Delete</td>
	</tr>
	<?php $i = 1; ?>
@foreach($apps as $app)
	<tr>
		<td>{{$i++}}</td>
    	<td><a href="https://play.google.com/store/apps/details?id={{ $app->package_name }}"><img src="application_icon/{{ $app->icon }}" width="50px" height="50px"></a></td>
    	<td>{{ $app->name }}</td>
    	<td>{{ $app->package_name }}</td>
        <td>{{ $app->application_id }}</td>
        <td>{{ $app->installed }}</td>
        <td><a href="{{ route('app.edit', $app->id) }}" class="btn btn-primary">Edit</a></td>
        <td><a href="{{ route('app.update_version', $app->id) }}" class="btn btn-primary">Update Version</a></td>
        <td>
        	{!! Form::open([
	            'method' => 'DELETE',
	            'route' => ['app.destroy', $app->id]
	        ]) !!}
	            {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure ?");']) !!}
	        {!! Form::close() !!}
        </td>
	</tr>
@endforeach
    </table>
	{!! $apps->links() !!}
</div>

@endsection