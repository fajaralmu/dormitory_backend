@extends('layouts.app')

@section('extra_style')
    <style>
        /* ul.opsi { margin: 0; padding: 0; }
        ul.opsi li {
            list-style: none;
            margin: 35px 0;
        } */

        div.circle {
            /*margin-right: 10px;*/
            /*padding: 10px 16px;*/
            width: 33px;
            height: 33px;
            padding-top: 3px;
            border: solid 1px #000;
            border-radius: 50%;
            background: #777;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
        }

        div.circle.incorrect {
            background: red;
        }

        div.circle.correct {
            background: green;
        }
    </style>
@endsection

@php
    $soal_essay = $cbt->soal->where('jenis', 'es');
@endphp

@section('head_script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML'></script>
@endsection

@section('content')
	<h1 class="title">CBT <span class="tag is-dark">Detail Hasil</span></h1>

	 <div class="level">
        <div class="level-left">
            <a href="{{ route('teacher.cbt.results.index', $cbt) }}" class="button is-outlined is-primary">
                <span class="icon">
                    <i class="fas fa-list"></i>
                </span>
                <span>Indeks Hasil</span>
            </a>
        </div>
        <div class="level-right">
            @if($soal_essay->count() > 0 && count(array_get($submisi, 'nilai_essay', [])) == $soal_essay->count() && ! $submisi->released)
                <form action="{{ route('teacher.cbt.results.lock.store', [$cbt, $submisi]) }}" method="POST">
                    @csrf
                    <button class="button is-{{ $submisi->score_locked ? 'danger' : 'dark' }}">
                        <span class="icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        @if($submisi->score_locked)
                            <span>Buka kunci Nilai</span>
                        @else
                            <span>Kunci Nilai</span>
                        @endif
                    </button>
                </form>
            @endif
        </div>
    </div>

    <h3 class="title is-4">{{ $submisi->siswa->user->name }} <span class="tag is-info">Kelas: {{ $submisi->siswa->kelas->level . ' ' . $submisi->siswa->kelas->rombel }}</span></h3>

	<h4 class="title is-4">Soal Essay</h4>
    @foreach(array_get($cbt->soal->groupBy('jenis'), 'es', []) as $item)
        <a name="{{ $item->urut }}"></a>
        <div style="height: 35px;"></div>
        <div class="box">
            <div class="level">
                <div class="level-left">
                    <div class="tags has-addons">
                        <span class="tag is-medium is-dark">Soal Nomor</span>
                        <span class="tag is-medium is-info">{{ $item->urut }}</span>
                    </div>
                </div>
                <div class="level-right">
                    {{-- <pre>{{ var_dump($item->urut, $submisi->nilai_essay) }}</pre> --}}
                    @if(in_array($item->urut, array_keys(array_get($submisi, 'nilai_essay', []))))
						<div class="has-text-success">
							<span class="icon">
								<i class="fas fa-check-circle"></i>
							</span>
							<span>Jawaban siswa sudah dinilai</span>
						</div>
                    @else
						<div class="has-text-danger">
							<span class="icon">
								<i class="fas fa-exclamation-triangle"></i>
							</span>
							<span>Jawaban siswa belum dinilai</span>
						</div>
                    @endif
                </div>
            </div>

            <div class="content">{!! $item->konten !!}</div>

            @if(! is_null(array_get($item->media, 'pertanyaan')))
                {{-- <figure class="image"> --}}
                	@php
                		$filename = array_get($item->media, 'pertanyaan.gambar.filename');
                	@endphp
                    <img src="{{ Storage::url('media/' . $filename) }}" width="300">
                {{-- </figure> --}}
            @endif

	        <div class="columns" style="margin-top: 10px;">
	        	<div class="column">
	        		@card(['title' => 'Jawaban Siswa'])
						{!! array_get($submisi->jawaban, array_get(array_flip($submisi->nomor_urut_teracak), $item->urut)) !!}
	        		@endcard
	        	</div>
	        	<div class="column">
	        		@card(['title' => 'Kunci Jawaban'])
						{!! $item->jawaban !!}
	        		@endcard
	        	</div>
	        </div>

            @if($submisi->score_locked)
                <div class="tags has-addons">
                    <span class="tag is-medium is-warning">Nilai</span>
                    <span class="tag is-medium is-success">{{ array_get($submisi->nilai_essay, $item->urut, 0) }}</span>
                </div>
            @else
                <form action="{{ route('teacher.cbt.essay-score.store', $cbt) }}" method="POST">
                    @csrf
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Nilai</label>
                        </div>
                        <div class="field-body">
                            <div class="field is-narrow">
                                <div class="control">
                                    <input type="hidden" name="submisi_id" value="{{ $submisi->id }}">
                                    <input name="nilai_essay[{{ $item->urut }}]" class="input{{ $errors->has('nilai_essay.' . $item->urut) ? ' is-danger' : '' }}" type="number" placeholder="Nilai / 100" value="{{ old('nilai_essay.' . $item->urut, array_get($submisi->nilai_essay, $item->urut, 0)) }}">
                                </div>
                                @if($errors->has('nilai_essay.' . $item->urut))
                                    <p class="help is-danger">{{ $errors->first('nilai_essay.' . $item->urut) }}</p>
                                @else
                                    <p class="help is-info">Nilai maksimal item essay adalah 100</p>
                                @endif
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button class="button is-primary">Update Nilai</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    @endforeach

    <h4 class="title is-4">Soal Pilihan Ganda</h4>
    @foreach(array_get($cbt->soal->groupBy('jenis'), 'pg', []) as $item)
        <a name="{{ $item->urut }}"></a>
        <div style="height: 35px;"></div>
        <div class="box">
            <div class="level">
                <div class="level-left">
                    <div class="tags has-addons">
                        <span class="tag is-medium is-dark">Soal Nomor</span>
                        <span class="tag is-medium is-info">{{ $item->urut }}</span>
                    </div>
                </div>
                <div class="level-right">
                	<div class="field is-grouped">
	                	<div class="control">
	                		<div class="tags has-addons">
		                        <span class="tag is-medium is-dark">Jawaban Siswa</span>
		                        <span class="tag is-medium is-info">{{ strtoupper(array_get($submisi->jawaban, array_get(array_flip($submisi->nomor_urut_teracak), $item->urut))) }}</span>
		                    </div>
	                	</div>
	                	<div class="control">
	                		<div class="tags has-addons">
		                        <span class="tag is-medium is-dark">Kunci Jawaban</span>
		                        <span class="tag is-medium is-info">{{ strtoupper($item->jawaban) }}</span>
		                    </div>
	                	</div>
                	</div>
                </div>
            </div>

            <div class="content">{!! $item->konten !!}</div>

            @if(! is_null(array_get($item->media, 'pertanyaan')))
                {{-- <figure class="image"> --}}
                	@php
                		$filename = array_get($item->media, 'pertanyaan.gambar.filename');
                	@endphp
                    <img src="{{ Storage::url('media/' . $filename) }}" width="300">
                {{-- </figure> --}}
            @endif

			<ul>
                @foreach($item->opsi as $key => $value)
                    <article class="media">
                        <figure class="media-left">
                            @php
                                $elementClass = 'circle';

                                if (in_array($item->urut, $submisi->nomor_urut_jawaban_salah) && $key == array_get($submisi->jawaban, array_get(array_flip($submisi->nomor_urut_teracak) ,$item->urut))) {
                                    $elementClass = $elementClass . ' incorrect';
                                }

                                if ($key == $item->jawaban) {
                                    $elementClass = $elementClass . ' correct';
                                }
                            @endphp
                            <div class="{{ $elementClass }}">{{ $key }}</div>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <div>{!! $value !!}</div>
                                @if(! is_null(array_get($item->media, 'opsi_' . $key)))
                                	@php
                                		$filename = array_get($item->media, 'opsi_' . $key . '.gambar.filename');
                                	@endphp
                                    <div><img src="{{ Storage::url('media/' . $filename) }}" width="300"></div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </ul>
        </div>
    @endforeach
@endsection