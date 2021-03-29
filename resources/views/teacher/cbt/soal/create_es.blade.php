@extends('layouts.app')

@section('content')
    <h1 class="title">CBT <span class="tag is-dark">SOAL</span></h1>

    <div class="level">
        <div class="level-left">
            <div class="field is-grouped">
                <div class="control">
                    <a href="{{ route('teacher.cbt.soal.index', $cbt) }}" class="button is-primary is-outlined">
                        <span class="icon">
                            <i class="fas fa-list"></i>
                        </span>
                        <span>Indeks Soal</span>
                    </a>
                </div>
                <div class="control">
                    <a href="{{ route('teacher.cbt.soal.show', [$cbt, $soal]) }}" class="button is-primary is-outlined">
                        <span class="icon">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span>Preview</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="level-right"></div>
    </div>

    @card(['title' => 'Buat Soal (' . config('school.cbt.jenis_pertanyaan')[$soal->jenis] . ')'])
        <form action="{{ route('teacher.cbt.soal.update', [$cbt, $soal]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="field">
                <label class="label">Nomor Urut</label>
                <div class="control">
                    <div class="select{{ $errors->has('urut') ? ' is-danger' : '' }}">
                        <select name="urut">
                            @php
                            array_push($remain_uruts, $soal->urut);
                            asort($remain_uruts);
                            @endphp
                            <option value="none">Pilih Nomor Urut</option>
                            @foreach($remain_uruts as $item)
                            <option value="{{ $item }}" {{ old('urut', $soal->urut) == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($errors->has('urut'))
                <p class="help is-danger">{{ $errors->first('urut') }}</p>
                @endif
            </div>
            
            <div class="field">
                <label class="label">Pertanyaan</label>
                <div class="control">
                    <textarea id="pertanyaan" name="pertanyaan" class="textarea" placeholder="Konten Pertanyaan">{{ old('pertanyaan', $soal->konten) }}</textarea>
                </div>
            </div>
            
            <question-media ujian="{{ $cbt->id }}" soal="{{ $soal->id }}" field="pertanyaan"></question-media>
            <hr>
            
            <div class="field">
                <label class="label">Jawaban</label>
                <div class="control">
                    <textarea id="jawaban" name="jawaban" class="textarea" placeholder="Konten Jawaban">{{ old('jawaban', $soal->jawaban) }}</textarea>
                </div>
            </div>
            
            <div class="field">
                <label class="label">Terkait soal sebelumnya?</label>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="terkait_soal_sebelumnya" value="1" {{ old('terkait_soal_sebelumnya', $soal->terkait_soal_sebelumnya) == 1 ? 'checked' : '' }}>
                        Yes
                    </label>
                    <label class="radio">
                        <input type="radio" name="terkait_soal_sebelumnya" value="0" {{ old('terkait_soal_sebelumnya', $soal->terkait_soal_sebelumnya) == 0 ? 'checked' : '' }}>
                        No
                    </label>
                </div>
            </div>
            
            <div class="field">
                <div class="control">
                    <button class="button is-link">Submit</button>
                </div>
            </div>
        </form>


        <media-preview></media-preview>

    @endcard
@endsection

@section('head_script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        window.documents = JSON.parse('@json($soal->media)')
    </script>
@endsection

@section('extra_script')
    @parent
    <script src="{{ mix('js/guru.js') }}"></script>
    <script>
        var toolbarGroups = [
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'links', groups: [ 'links' ] },
            '/',
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ];
        var removeButtons = 'Save,Templates,NewPage,Preview,Print,Cut,Copy,Paste,PasteText,PasteFromWord,ShowBlocks,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Link,Unlink,BidiRtl,BidiLtr,Language,Anchor,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,CopyFormatting,RemoveFormat,Superscript,Subscript,Outdent,Indent,Blockquote,CreateDiv,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt,SelectAll,Replace,Find,Undo,Redo,About,Styles,FontSize,Font';
        var mathJaxLib = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-AMS_HTML';

        var inputs = ['pertanyaan', 'jawaban'];

        for (var i = 0; i < inputs.length; i++) {
            CKEDITOR.replace( inputs[i], {
                extraPlugins: 'mathjax',
                mathJaxLib: mathJaxLib,
                height: 250,
                toolbarGroups: toolbarGroups,
                removeButtons: removeButtons
            } );
        }

        if ( CKEDITOR.env.ie && CKEDITOR.env.version == 8 ) {
            document.getElementById( 'ie8-warning' ).className = 'tip alert';
        }
    </script>
@endsection
