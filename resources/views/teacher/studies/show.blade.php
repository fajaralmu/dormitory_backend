@extends('layouts.app')

@section('content')
	<div class="level">
		<div class="level-left">
			<p class="subtitle">
				<a href="{{ route('teacher.studies.index') }}">
					<span class="fa-stack fa-xs">
						<i class="fas fa-circle fa-stack-2x"></i>
						<i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
			 		</span>
					<span>Studies</span>
				</a>
			</p>
		</div>
	</div>

	<h1 class="title">{{ $study->title }}</h1>
	<div class="box">{{ $study->description }}</div>

	<h4 class="title is-4">Lessons</h4>

	<div class="level">
		<div class="level-left">
			<a href="{{ route('teacher.studies.lessons.create', $study) }}" class="button is-primary is-rounded">
				<span class="icon">
					<i class="fas fa-plus"></i>
				</span>
				<span>Create lesson</span>
			</a>
		</div>
	</div>

	@if($study->lessons->count())
		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
					<th>#</th>
					<th>Title</th>
					<th class="has-text-centered">Duration</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($study->lessons as $lesson)
					<tr>
						<td>{{ $lesson->number }}</td>
						<td>
							<a href="{{ route('teacher.studies.lessons.show', [$study, $lesson]) }}">
								{{ $lesson->title }}
							</a>
						</td>
						<td class="has-text-centered">{{ $lesson->video_duration }}</td>
						<td class="has-text-right">
							<a href="{{ route('teacher.studies.lessons.edit', [$study, $lesson]) }}" class="button is-text">
								<i class="fas fa-edit"></i>
							</a>
							<form action="{{ route('teacher.studies.lessons.destroy', [$study, $lesson]) }}" method="POST" style="display: inline;">
	                            @csrf
	                            @method('DELETE')
	                            <button name="delete-item" class="button is-text has-text-danger">
	                                <span class="icon">
	                                    <i class="fas fa-trash"></i>
	                                </span>
	                            </button>
	                        </form>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<div class="notification is-warning">
			<span class="icon">
				<i class="fas fa-exclamation-triangle"></i>
			</span>
			<span>No lesson for this study yet. please create new one.</span>
		</div>
	@endif

	@section('modal')
		@modal(['modal_id' => 'delete-confirmation', 'close_btn' => true])
			@include('partials.delete-confirmation')
		@endmodal
	@endsection
@endsection