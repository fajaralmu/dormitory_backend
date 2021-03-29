@extends('layouts.app')

@section('content')
	<h1 class="title">CBT</h1>

	@card(['title' => 'Bobot Soal CBT'])
		{!! $form !!}
	@endcard
@endsection