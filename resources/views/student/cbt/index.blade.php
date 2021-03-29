@extends('layouts.app')

@section('content')
	<h1 class="title">CBT</h1>

	@if($cbts->count())
		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
					<th class="has-text-centered">#</th>
					<th>Mapel</th>
					<th class="has-text-centered">Jenis</th>
					<th>Tanggal</th>
					<th class="has-text-centered">Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($cbts as $cbt)
					@php
			    		$submisi = $submisis->where('cbt_id', $cbt->id)->first();
			    		$locked = array_get($submisi, 'locked');
			    		$route = $locked ? '#' : route('student.cbt.show', $cbt);
						$isForToday = $cbt->tanggal_pelaksanaan->isToday();
			    	@endphp
					<tr>
						<td class="has-text-centered">{{ $loop->iteration }}</td>
						<td>{{ $cbt->mapel->nama }}</td>
						<td class="has-text-centered">{{ strtoupper($cbt->jenis) }}</td>
						<td>{{ $cbt->tanggal_pelaksanaan->format('d F Y') }}</td>
						<td class="has-text-centered">
							<span class="icon has-text-{{ $locked ? 'success' : 'warning' }}">
								<i class="fas fa-{{ $locked ? 'check-circle' : 'question-circle' }}"></i>
							</span>
						</td>
						<td class="has-text-right">
							@if($isForToday)
								<a href="{{ $cbt->active ? $route : '#' }}" class="button is-outlined is-success"  {{ $cbt->active  && ! $locked ? '' : 'disabled' }}>
									<span class="icon">
										<i class="fas fa-{{ $locked ? 'file' : 'play' }}"></i>
									</span>
									@if(is_null($submisi))
										<span>Mulai</span>
									@else
										<span>{{ $locked ? 'Selesai' : 'Lanjutkan' }}</span>
									@endif
								</a>
							@else
								<a href="#" class="button is-warning">
									<span class="icon">
										<i class="fas fa-clock"></i>
									</span>
									<span>{{ $locked ? 'Selesai' : 'Menunggu' }}</span>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
@endsection