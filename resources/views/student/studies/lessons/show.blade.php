@extends('layouts.app')

@section('extra_style')
	<link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
	<style>
		.video-js .vjs-big-play-button {
		    font-size: 3em;
		    line-height: 1.5em;
		    height: 1.63332em;
		    width: 3em;
		    display: block;
		    position: absolute;
		    top: 40%;
		    left: 40%;
		    padding: 0;
		    cursor: pointer;
		    opacity: 1;
		    border: .06666em solid #fff;
		    background-color: #2b333f;
		    background-color: rgba(43,51,63,.7);
		    border-radius: .3em;
		    transition: all .4s;
		}
	</style>
@endsection

@section('head_script')
	<script src="{{ asset('js/video.min.js') }}"></script>
@endsection

@php
	$fields = [
		"video_url",
		"video_duration",
		// "video_embed_type",
		"material_file",
		"practice_required",
		"practice_file_mimes",
	];

	$extras = array_only($lesson->toArray(), $fields);
@endphp

@section('content')
	<nav class="breadcrumb has-arrow-separator" aria-label="breadcrumbs">
    	<ul>
    		<li>
    			<a href="{{ route('student.studies.index') }}">Studies</a>
    		</li>
    		<li>
    			<a href="{{ route('student.studies.show', $study) }}">{{ $study->title }}</a>
    		</li>
    	</ul>
    </nav>
	<h1 class="title">
		<span class="has-text-grey">#{{ $lesson->number }}</span> 
		{{ $lesson->title }}
	</h1>
	@if($lesson->practice_required)
		<div class="field is-grouped">
			<div class="control">
				<input type="file" name="project" class="input">
			</div>
			<div class="control">
				<button class="button is-dark">
					<span class="icon">
						<i class="fas fa-upload"></i>
					</span>
					<span>Upload Practical Project</span>
				</button>
			</div>
		</div>
	@endif
	<div class="content">
		<div class="box">{!! nl2br($lesson->content) !!}</div>
	</div>

	@if(! is_null($lesson->material_file))
		<div style="padding: 25px;" class="has-text-centered">
			<a href="{{ Storage::url('materials/' . $lesson->material_file) }}" target="_blank" class="button is-primary">
				<span class="icon">
					<i class="fas fa-download"></i>
				</span>
				<span>Download Project Material</span>
			</a>
		</div>
	@endif

	<div class="level">
		<div id="video-tutorial" class="level-item">
			<video id="example_video_1" class="video-js" controls preload="none" width="688" height="385" poster="{{ asset('img/snapshot.png') }}" data-setup="{}">
				<source src="{{ asset('videos/sample.mp4') }}" type="video/mp4">
				<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			</video>
		</div>
	</div>
@endsection