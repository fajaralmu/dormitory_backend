@extends('layouts.app')

@section('content')
	<h1 class="title">CBT</h1>

	@card(['title' => 'Edit CBT'])
		{!! $form !!}
	@endcard
@endsection