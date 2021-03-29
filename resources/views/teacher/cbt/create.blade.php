@extends('layouts.app')

@section('content')
	<h1 class="title">CBT</h1>

	@card(['title' => 'Create CBT'])
		{!! $form !!}
	@endcard
@endsection