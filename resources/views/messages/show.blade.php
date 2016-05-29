@extends('layouts.app')

@section('content')

<h1>{{ $message->title }}</h1>
<p class="lead">{{ $message->content }}</p>
<hr>

<a href="{{ route('gcm.index') }}" class="btn btn-info">Back to all tasks</a>
<a href="{{ route('gcm.edit', $message->id) }}" class="btn btn-primary">Edit Task</a>

<div class="col-md-6 text-right">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['gcm.destroy', $message->id]
        ]) !!}
            {!! Form::submit('Delete this task?', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>

@endsection