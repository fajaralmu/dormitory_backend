@extends('layouts.app')

@section('content')
    <h1 class="title">CBT <span class="tag is-dark">SOAL</span></h1>

    <div class="level">
        <div class="level-left">
            <div class="field is-grouped">
                <div class="control">
                    <a href="{{ route('teacher.cbt.index') }}" class="button is-outlined is-primary">
                        <span class="icon">
                            <i class="fas fa-list"></i>
                        </span>
                        <span>Indeks CBT</span>
                    </a>
                </div>

                @if($cbt->soal->count() < $cbt->jumlah_soal)
                    <div class="control">
                        <div class="dropdown is-hoverable">
                            <div class="dropdown-trigger">
                                <button class="button is-primary" aria-haspopup="true" aria-controls="dropdown-menu4">
                                    <span>Buat Soal</span>
                                    <span class="icon is-small">
                                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                                <div class="dropdown-content">
                                    <a href="{{ route('teacher.cbt.soal.create', [$cbt, 'type' => 'pg']) }}" class="dropdown-item">Soal Pilihan Ganda</a>
                                    <a href="{{ route('teacher.cbt.soal.create', [$cbt, 'type' => 'es']) }}" class="dropdown-item">Soal Essay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="level-right"></div>
    </div>

    <table class="table is-fullwidth is-vcentered">
        <thead>
            <tr>
                <th>Urut</th>
                <th>Jenis</th>
                <th>Pertanyaan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($cbt->soal as $soal)
                <tr>
                    <td>{{ $soal->urut }}</td>
                    <td>
                        <span class="tag is-{{ $soal->jenis == 'pg' ? 'info' : 'warning' }}">
                            {{ strtoupper($soal->jenis) }}
                        </span>
                    </td>
                    <td>{{ strip_tags(Str::limit($soal->konten, 85)) }}</td>
                    <td class="has-text-right">
                        <a href="{{ route('teacher.cbt.soal.show', [$cbt, $soal]) }}" class="button is-text has-text-dark">
                            <span class="icon">
                                <i class="fas fa-file-alt"></i>
                            </span>
                        </a>
                        @if(! $cbt->published)
                            <a href="{{ route('teacher.cbt.soal.edit', [$cbt, $soal]) }}" class="button is-text has-text-primary">
                                <span class="icon">
                                    <i class="fas fa-edit"></i>
                                </span>
                            </a>

                            <form action="{{ route('teacher.cbt.soal.destroy', [$cbt, $soal]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button name="delete-item" class="button is-text has-text-danger">
                                    <span class="icon">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </button>
                            </form>
                        @endif
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
@endsection