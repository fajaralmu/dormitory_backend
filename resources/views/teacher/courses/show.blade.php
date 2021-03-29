@extends('layouts.app')

@section('content')
	<p class="subtitle">
		<a href="{{ route('teacher.courses.index') }}">
			<span class="fa-stack fa-xs">
				<i class="fas fa-circle fa-stack-2x"></i>
				<i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
	 		</span>
			<span>Courses</span>
		</a>
	</p>
	<h1 class="title">{{ $course->title }}</h1>
	<div class="box">{{ $course->description }}</div>

	<h4 class="title is-4">Episodes</h4>
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
			@foreach($course->episodes as $episode)
				<tr>
					<td>{{ $episode->number }}</td>
					<td>
						<a href="{{ route('teacher.courses.episodes.show', [$course, $episode]) }}">
							{{ $episode->title }}
						</a>
					</td>
					<td class="has-text-centered">{{ $episode->video_duration }}</td>
					<td class="has-text-right">
						<a href="" class="button is-dark">
							<i class="fas fa-edit"></i>
						</a>
						<button class="button is-danger">
							<i class="fas fa-trash"></i>
						</button>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection