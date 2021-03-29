@extends('layouts.app')

@section('content')
	<nav class="breadcrumb has-arrow-separator" aria-label="breadcrumbs">
    	<ul>
    		<li>
    			<a href="{{ route('teacher.studies.index') }}">Studies</a>
    		</li>
    		<li>
    			<a href="{{ route('teacher.studies.show', $study) }}">{{ $study->title }}</a>
    		</li>
    	</ul>
    </nav>

    @card(['title' => 'Create Lesson'])
    	{!! $form !!}
    @endcard
@endsection

@section('head_script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection

@section('extra_script')
    @parent
    <script>
        var toolbarGroups = [
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'links', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'clipboard', groups: [ 'undo' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'about', groups: [ 'about' ] }
        ];

        CKEDITOR.replace( 'content', {
            height: 230,
            toolbarGroups: toolbarGroups,
        } );

        if ( CKEDITOR.env.ie && CKEDITOR.env.version == 8 ) {
            document.getElementById( 'ie8-warning' ).className = 'tip alert';
        }
    </script>
@endsection