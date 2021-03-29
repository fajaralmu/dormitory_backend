@extends('layouts.app')

@php
	$fields = [
		"video_url",
		// "video_url_type",
		"video_duration",
		"material_file",
		"practice_file_mimes",
	];

	$setups = array_only($lesson->toArray(), ['practice_required', 'published', 'is_free']);
	$extras = array_only($lesson->toArray(), $fields);
@endphp

@section('content')
	<nav class="breadcrumb has-arrow-separator" aria-label="breadcrumbs">
    	<ul>
    		<li>
    			<a href="{{ route('teacher.studies.index') }}">Studies</a>
    		</li>
    		<li>
    			<a href="{{ route('teacher.studies.show', $study) }}">{{ $study->title }}</a>
    		</li>
    	</ul>
    </nav>
    <div class="level">
    	<div class="level-left">
    		<a href="{{ route('teacher.studies.lessons.edit', [$study, $lesson]) }}" class="button is-primary">
    			<span class="icon">
    				<i class="fas fa-edit"></i>
    			</span>
    			<span>Edit</span>
    		</a>
    	</div>
    	<div class="level-right">
    		<form action="{{ route('teacher.studies.lessons.destroy', [$study, $lesson]) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button name="delete-item" class="button is-danger">
                    <span class="icon">
                        <i class="fas fa-trash"></i>
                    </span>
                    <span>Delete</span>
                </button>
            </form>
    	</div>
    </div>
	<h1 class="title">
		<span class="has-text-grey">#{{ $lesson->number }}</span> 
		{{ $lesson->title }}
	</h1>
	<div class="columns">
		<div class="column">
			<ul>
				@foreach($extras as $key => $value)
					<li>
						<span class="icon">
							<i class="fas fa-minus"></i>
						</span>
						<span>{{ str_replace('_', ' ', $key) }}: <strong>{{ is_array($value) ? implode(', ', $value) : $value }}</strong></span>
					</li>
				@endforeach
			</ul>
		</div>
		<div class="column">
			<ul>
				@foreach($setups as $key => $value)
					<li>
						<span class="icon has-text-{{ $value ? 'success' : 'danger' }}">
							<i class="fas fa-{{ $value ? 'check' : 'times' }}-circle"></i>
						</span>
						<span>{{ str_replace('_', ' ', $key) }}</span>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="content">
		<div class="box">{!! nl2br($lesson->content) !!}</div>
	</div>

	@section('modal')
		@modal(['modal_id' => 'delete-confirmation', 'close_btn' => true])
			@include('partials.delete-confirmation')
		@endmodal
	@endsection
@endsection