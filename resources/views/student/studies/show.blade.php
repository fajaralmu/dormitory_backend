@extends('layouts.app')

@section('content')
	<p class="subtitle">
		<a href="{{ route('student.studies.index') }}">
			<span class="fa-stack fa-xs">
				<i class="fas fa-circle fa-stack-2x"></i>
				<i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
	 		</span>
			<span>Studies</span>
		</a>
	</p>
	<h1 class="title">{{ $study->title }}</h1>
	<div class="box">{{ $study->description }}</div>

	<h4 class="title is-4">Lessons</h4>
	<table class="table is-fullwidth is-vcentered">
		<thead>
			<tr>
				<th>#</th>
				<th>Title</th>
				<th class="has-text-centered">Duration</th>
			</tr>
		</thead>
		<tbody>
			@foreach($study->lessons as $lesson)
				<tr>
					<td>{{ $lesson->number }}</td>
					<td>
						<a href="{{ route('student.studies.lessons.show', [$study, $lesson]) }}">
							{{ $lesson->title }}
						</a>
					</td>
					<td class="has-text-centered">{{ $lesson->video_duration }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection