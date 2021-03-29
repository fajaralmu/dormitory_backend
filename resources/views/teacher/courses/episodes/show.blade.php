@extends('layouts.app')

@php
	$fields = [
		"video_url",
		"video_duration",
		// "video_embed_type",
		"material_file",
		// "practice_required",
		// "practice_file_mimes",
	];

	$extras = array_only($episode->toArray(), $fields);
	// var_dump($extras);
@endphp

@section('content')
	{{-- <div class="notification is-info">
		<span class="icon">
			<i class="fas fa-briefcase"></i>
 		</span>
		<span>{{ $course->title }}</span>
	</div> --}}
	<nav class="breadcrumb has-arrow-separator" aria-label="breadcrumbs">
    	<ul>
    		<li>
    			<a href="{{ route('teacher.courses.index') }}">Courses</a>
    		</li>
    		<li>
    			<a href="{{ route('teacher.courses.show', $course) }}">{{ $course->title }}</a>
    		</li>
    	</ul>
    </nav>
	<h1 class="title">
		<span class="has-text-grey">#{{ $episode->number }}</span> 
		{{ $episode->title }}
	</h1>
	<div class="content">
		<ul>
			@foreach($extras as $key => $value)
				<li>{{ str_replace('_', ' ', $key) }}: <strong>{{ $value }}</strong></li>
			@endforeach
		</ul>
		<div class="box">{!! nl2br($episode->content) !!}</div>
	</div>
@endsection