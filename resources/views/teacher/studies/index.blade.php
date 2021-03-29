@extends('layouts.app')

@section('content')
	<h1 class="title">Studies</h1>

	<div class="level">
		<div class="level-left">
			<a href="{{ route('teacher.studies.create') }}" class="button is-primary is-rounded">
				<span class="icon">
					<i class="fas fa-plus"></i>
				</span>
				<span>Create Study</span>
			</a>
		</div>
	</div>

	@if($studies->count())
		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
					<th>#</th>
					<th>Title</th>
					<th class="has-text-centered">Lessons</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($studies as $study)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>
							<a href="{{ route('teacher.studies.show', $study) }}">
								{{ $study->title }}
							</a>
						</td>
						<td class="has-text-centered">{{ $study->lessons_count }}</td>
						<td class="has-text-right">
							<a href="{{ route('teacher.studies.edit', $study) }}" class="button is-text">
								<i class="fas fa-edit"></i>
							</a>

							<form action="{{ route('teacher.studies.destroy', $study) }}" method="POST" style="display: inline;">
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
			<span>You have no study yet. please create new one.</span>
		</div>
	@endif

	@section('modal')
		@modal(['modal_id' => 'delete-confirmation', 'close_btn' => true])
			@include('partials.delete-confirmation')
		@endmodal
	@endsection
@endsection
