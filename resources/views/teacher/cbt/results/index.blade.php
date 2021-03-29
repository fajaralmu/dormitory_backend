@extends('layouts.app')

@php
	$submisis = $cbt->submisi;
@endphp

@section('content')
	<h1 class="title">CBT</h1>

	<table class="table is-fullwidth is-vcentered">
    	<thead>
    		<tr>
    			<th>#</th>
    			<th>Nama Siswa</th>
    			<th class="has-text-centered">Jawaban Benar</th>
                <th class="has-text-centered">Nilai</th>
                @if(count($cbt->soal->where('jenis', 'es')))
                    <th class="has-text-centered">Check</th>
                @endif
    			<th></th>
    		</tr>
    	</thead>
    	<tbody>
    		@foreach($submisis as $submisi)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ array_get($students, $submisi->siswa_id) }}</td>
					<td class="has-text-centered">{{ count(array_get($submisi, 'nomor_urut_jawaban_benar', [])) }}</td>
					<td class="has-text-centered">{{ $submisi->nilai }}</td>
                    @if(count($cbt->soal->where('jenis', 'es')))
                        @php
                            $nilai_essay = array_get($submisi, 'nilai_essay', []);
                        @endphp
                        <td class="has-text-centered">
                            {{-- @if(! $cbt->soal->where('jenis', 'es')->pluck('urut')->diff(array_keys($nilai_essay))->count()) --}}
                            @if($submisi->score_locked)
                                <span class="icon has-text-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @else
                                <span class="icon has-text-warning">
                                    <i class="fas fa-question-circle"></i>
                                </span>
                            @endif
                        </td>
                    @endif
					<td class="has-text-right">
						<a href="{{ route('teacher.cbt.results.show', [$cbt, $submisi->id]) }}" class="button is-outlined is-primary">Detail</a>
					</td>
				</tr>
    		@endforeach
    	</tbody>
    </table>
@endsection