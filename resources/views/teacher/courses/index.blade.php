@extends('layouts.app')

@section('content')
	<h1 class="title">Courses</h1>

	<div class="level">
		<div class="level-left">
			<a href="{{ route('teacher.courses.create') }}" class="button is-primary is-rounded">
				<span class="icon">
					<i class="fas fa-plus"></i>
				</span>
				<span>Create Course</span>
			</a>
		</div>
	</div>

	@if($courses->count())
		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
					<th>#</th>
					<th>Title</th>
					<th class="has-text-centered">Episodes</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($courses as $course)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>
							<a href="{{ route('teacher.courses.show', $course) }}">
								{{ $course->title }}
							</a>
						</td>
						<td class="has-text-centered">{{ $course->episodes_count }}</td>
						<td class="has-text-right">
							<a href="{{ route('teacher.courses.edit', $course) }}" class="button is-dark">
								<i class="fas fa-edit"></i>
							</a>

							<form action="{{ route('teacher.courses.destroy', $course) }}" method="POST" style="display: inline;">
	                            @csrf
	                            @method('DELETE')
	                            <button name="delete-item" class="button is-danger">
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
			<span>You have no course yet. please create new one.</span>
		</div>
	@endif

	@section('modal')
		@modal(['modal_id' => 'delete-confirmation', 'close_btn' => true])
			@include('partials.delete-confirmation')
		@endmodal
	@endsection
@endsection
