@extends('layouts.app')

@section('extra_style')
    <style>
        div.circle {
            /*margin-right: 10px;*/
            /*padding: 10px 16px;*/
            width: 50px;
            height: 50px;
            padding-top: 12px;
            border: solid 1px #000;
            border-radius: 50%;
            background: #777;
            color: #fff;
            text-transform: uppercase;
        }

        div.circle.correct {
            background: green;
        }
    </style>
@endsection

@section('head_script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML'></script>
@endsection

@section('content')
    <h1 class="title">CBT <span class="tag is-dark">SOAL</span></h1>

    <div class="level">
        <div class="level-left">
            <div class="field is-grouped">
                <div class="control">
                    <a href="{{ route('teacher.cbt.soal.index', $cbt) }}" class="button is-outlined is-primary">
                        <span class="icon">
                            <i class="fas fa-list"></i>
                        </span>
                        <span>Indeks Soal</span>
                    </a>
                </div>
                @if(! $cbt->published)
                    <div class="control">
                        <a href="{{ route('teacher.cbt.soal.edit', [$cbt, $soal]) }}" class="button is-outlined is-primary">
                            <span class="icon">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span>Edit</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="level-right">
            <div class="field is-grouped">
                <div class="control">
                    <div class="tags has-addons">
                        <span class="tag is-dark">Jenis</span>
                        <span class="tag is-warning">{{ strtoupper($cbt->jenis) }}</span>
                    </div>
                </div>

                <div class="control">
                    <div class="tags has-addons">
                        <span class="tag is-dark">Mapel</span>
                        <span class="tag is-warning">{{ $cbt->mapel->nama }}</span>
                    </div>
                </div>

                {{-- <div class="control">
                    <div class="tags has-addons">
                        <span class="tag is-dark">Kelas</span>
                        <span class="tag is-warning">{{ $cbt->kelas }}</span>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    @card(['title' => 'Preview Soal'])
    	<p>
            <div class="tags has-addons">
                <span class="tag is-medium is-dark">Soal Ke</span>
                <span class="tag is-medium is-info">{{ $soal->urut }}</span>
            </div>
        </p>
        <div class="content">
            <div class="box">
                {!! $soal->konten !!}

                @if(! is_null($soal->media['pertanyaan']))
                    @foreach(['gambar', 'audio', 'video'] as $jenis)
                        @if(! is_null(array_get($soal->media['pertanyaan'], $jenis)))
                            @php
                                $params = [
                                    'jenis' => $jenis,
                                    'filename' => array_get($soal->media['pertanyaan'][$jenis], 'filename')
                                ];
                            @endphp
                            @include('teacher.cbt.soal.media', $params)
                        @endif
                    @endforeach
                @endif
            </div>

            @if($soal->jenis == 'pg')
                @foreach($soal->opsi as $key => $value)
                    <article class="media">
                        <figure class="media-left">
                           <div class="circle{{ $key == $soal->jawaban ? ' correct' : '' }}">{{ $key }}</div>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <div>{!! $value !!}</div>
                                @if(! is_null($soal->media['opsi_' . $key]))
                                    @foreach(['gambar', 'audio', 'video'] as $jenis)
                                        @if(! is_null(array_get($soal->media['opsi_' . $key], $jenis)))
                                            @php
                                                $params = [
                                                    'jenis' => $jenis,
                                                    'filename' => array_get($soal->media['opsi_' . $key][$jenis], 'filename')
                                                ];
                                            @endphp
                                            @include('teacher.cbt.soal.media', $params)
                                        @endif
                                    @endforeach
                                @endif 
                            </div>
                        </div>
                    </article>
                @endforeach
            @else
                <p><strong>Jawaban:</strong><br>{!! $soal->jawaban !!}</p>
            @endif
        </div>
    @endcard
@endsection