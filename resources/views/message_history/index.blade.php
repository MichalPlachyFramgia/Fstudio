@extends('layouts.app')

@section('content')
<div class="container">
	<h3>Google Cloud Message History</h3>
	<hr>
	<table class="table table-bordered">
	<tr>
		<td>#</td>
        <td>Success/All</td>
        <td>Sent time</td>
	</tr>
	<?php $i = 1; ?>
@foreach($message_histories as $message_history)
	<tr>
		<td>{{$i++}}</td>
    	<td>{{ $message_history->success }}/{{ $message_history->success + $message_history->failed }}</td>
    	<td>{{ date("d-m-Y h:i:s a",strtotime($message_history->created_at)+3600*7) }}</td>
	</tr>
@endforeach
    </table>
	{!! $message_histories->links() !!}
</div>

@endsection