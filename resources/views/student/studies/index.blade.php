@extends('layouts.app')

@section('content')
	<h1 class="title">Studies</h1>

	<div class="notification is-info">
		<span class="icon">
			<i class="fas fa-info-circle"></i>
		</span>
		<span>Studies below are for your current level (kelas)</span>
	</div>

	<table class="table is-fullwidth is-vcentered">
		<thead>
			<tr>
				<th>#</th>
				<th>Mapel</th>
				<th>Title</th>
				<th class="has-text-centered">Lessons</th>
			</tr>
		</thead>
		<tbody>
			@foreach($studies as $study)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>
						{{ $study->mapel->nama }}
					</td>
					<td>
						<a href="{{ route('student.studies.show', $study) }}">
							{{ $study->title }}
						</a>
					</td>
					<td class="has-text-centered">{{ $study->lessons_count }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection