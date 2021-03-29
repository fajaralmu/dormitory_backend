@extends('layouts.app')

@section('content')
	<div class="level">
		<div class="level-left">
			<p class="subtitle">
				<a href="{{ route('teacher.studies.index') }}">
					<span class="fa-stack fa-xs">
						<i class="fas fa-circle fa-stack-2x"></i>
						<i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
			 		</span>
					<span>Studies</span>
				</a>
			</p>
		</div>
	</div>

	@card(['title' => 'Create Study'])
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

        CKEDITOR.replace( 'description', {
            height: 230,
            toolbarGroups: toolbarGroups,
        } );

        if ( CKEDITOR.env.ie && CKEDITOR.env.version == 8 ) {
            document.getElementById( 'ie8-warning' ).className = 'tip alert';
        }
    </script>
@endsection
