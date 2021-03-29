@extends('layouts.app')

@section('content')
	<h1 class="title">CBT</h1>

	<div class="level">
		<div class="level-left">
		<a href="{{ route('teacher.cbt.create') }}" class="button is-primary is-rounded">
			<span class="icon">
				<i class="fas fa-plus"></i>
			</span>
			<span>Create CBT</span>
		</a>
		</div>
	</div>

	@if($cbts->count())
		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
					<th>Mapel</th>
                    <th>Kelas</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th class="has-text-centered">Soal</th>
                    <th class="has-text-centered">
                        <i class="fas fa-rocket"></i>
                    </th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($cbts as $cbt)
					<tr>
						<td>{{ $cbt->mapel->nama }}</td>
						<td>{{ $cbt->kelas->level . $cbt->kelas->rombel }}</td>
						<td>{{ $cbt->jenis }}</td>
						<td>{{ $cbt->tanggal_pelaksanaan->format('d F Y') }}</td>
						<td class="has-text-centered">{{ $cbt->jumlah_soal }}</td>
						<td class="has-text-centered">
							<i class="fas fa-{{ $cbt->published ? 'check-circle has-text-success' : 'question-circle has-text-warning' }}"></i>
						</td>
						<td class="has-text-right">
							@if(! $cbt->published)
                                @if($cbt->soal->count() == $cbt->jumlah_soal)
                                    @if($cbt->soal->where('jenis', 'es')->count())
                                        <a href="{{ route('teacher.cbt.bobot.create', $cbt) }}" class="button is-text has-text-dark">
                                            <i class="fas fa-rocket"></i>
                                        </a>
                                    @else
                                        <form action="{{ route('teacher.cbt.publish.store', $cbt) }}" method="POST" style="display: inline;">
                                            {{ csrf_field() }}
                                            <button class="button is-text has-text-dark" title="Publish" alt="Publish">
                                                <i class="fas fa-rocket"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
							

								<a href="{{ route('teacher.cbt.edit', $cbt) }}" class="button is-text has-text-primary">
									<i class="fas fa-edit"></i>
								</a>

								<form action="{{ route('teacher.cbt.destroy', $cbt) }}" method="POST" style="display: inline;">
		                            @csrf
		                            @method('DELETE')
		                            <button name="delete-item" class="button is-text has-text-danger">
		                                <span class="icon">
		                                    <i class="fas fa-trash"></i>
		                                </span>
		                            </button>
		                        </form>
		                    @endif

		                    <a href="{{ route('teacher.cbt.soal.index', $cbt) }}" class="button is-text has-text-dark">
								<i class="fas fa-list"></i>
							</a>
							<a href="{{ route('teacher.cbt.results.index', $cbt) }}" class="button is-text has-text-info">
								<i class="fas fa-play"></i>
							</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		@section('modal')
	        @modal(['modal_id' => 'delete-confirmation', 'close_btn' => true])
	            @include('partials.delete-confirmation')
	        @endmodal
	    @endsection
	@endif
@endsection