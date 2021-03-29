@extends('layouts.app')

@section('content')
	<h1 class="title">CBT</h1>

	<div class="level">
    	<div class="level-left">
	    	@if(! $cbt->finish_at)
				<form action="{{ route('teacher.cbt.activation.store', $cbt) }}" method="POST">
	    			@csrf
	    			@if($cbt->submisi->count() && $cbt->submisi->where('locked', false)->count() == 0)
	    				<input type="hidden" name="finished" value="1">
						<button class="button is-primary">
			    			<span class="icon">
			    				<i class="fas fa-stop"></i>
			    			</span>
			    			<span>Selesai</span>
			    		</button>
	    			@else
		    			<button class="button is-outlined is-{{ $cbt->active ? 'danger' : 'success' }}">
			    			<span class="icon">
			    				<i class="fas fa-{{ $cbt->active ? 'pause' : 'play' }}"></i>
			    			</span>
			    			<span>{{ $cbt->active ? 'Tutup' : 'Buka' }} Akses</span>
			    		</button>
		    		@endif
	    		</form>
	    	@else
	    		<a href="{{ route('teacher.cbt.results.index', $cbt) }}" class="button button is-primary is-outlined">
	    			Detil Hasil CBT
	    		</a>
	    	@endif
    	</div>
    	<div class="level-right">
    		<div class="field is-grouped">
    			<div class="control">
    				<div class="tags has-addons">
    					<span class="tag is-medium is-dark">{{ strtoupper($cbt->jenis) }}</span>
    					<span class="tag is-medium is-info">{{ $cbt->mapel->nama }}</span>
    				</div>
    			</div>
    			<div class="control">
    				<div class="tags has-addons">
    					<span class="tag is-medium is-dark">Tanggal</span>
    					<span class="tag is-medium is-info">{{ $cbt->tanggal_pelaksanaan->format('d F Y') }}</span>
    				</div>
    			</div>

    			<div class="control">
    				<div class="tags has-addons">
    					<span class="tag is-medium is-dark">Kelas</span>
    					<span class="tag is-medium is-info">{{ $cbt->kelas->level . $cbt->kelas->rombel }}</span>
    				</div>
    			</div>

    			<div class="control">
    				<div class="tags has-addons">
    					<span class="tag is-medium is-dark">{{ $cbt->jumlah_soal }} Soal</span>
    					<span class="tag is-medium is-info">{{ $cbt->durasi }} menit</span>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>

	@card(['title' => 'Monitor CBT'])
		@if($cbt->submisi->count())
			<table class="table is-fullwidth is-vcentered">
				<thead>
					<tr>
						<th>#</th>
						<th>Nama Siswa</th>
						<th class="has-text-centered">Mulai</th>
						<th class="has-text-centered">Terjawab</th>
						<th class="has-text-centered">Selesai</th>
						{{-- <th>Sisa Waktu</th> --}}
						{{-- <th></th> --}}
					</tr>
				</thead>
				<tbody>
					@foreach($cbt->submisi as $item)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ array_get($students, $item->siswa_id) }}</td>
							<td class="has-text-centered">{{ $item->start_at->format('H:i') }}</td>
							<td class="has-text-centered">{{ count($item->jawaban) }}</td>
							<td class="has-text-centered">
								<span class="icon has-text-{{ $item->locked ? 'success' : 'warning' }}">
									<i class="fas fa-{{ $item->locked ? 'check-circle' : 'question-circle' }}"></i>
								</span>
							</td>
							{{-- <td>{{ $item->sisa_waktu > 60 ? number_format($item->sisa_waktu / 60, 0) : 0 }} <small class="has-text-grey">menit</small></td> --}}
							{{-- <td>
								<i class="fas fa-user"></i>
							</td> --}}
						</tr>
					@endforeach
				</tbody>
			</table>
	    @else
			<div class="notification is-info">
				<i class="fas fa-info-circle fa-lg"></i> 
				<span style="padding-left: 5px;">Siswa belum berpartisipasi dalam CBT ini.</span>
			</div>
	    @endif
	@endcard
@endsection