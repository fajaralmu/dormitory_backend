<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="has-navbar-fixed-top">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/bulma.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <style>

		#status {
            position: fixed;
            padding: 25px;
            top: 60px;
            left: 20px;
            /*right: 350px;
            left: 350px;*/
            min-width: 350px;
		    margin-right: auto;
		    margin-left: auto;
            z-index: 999999;
            border: solid 1px #c3b33b;
        } 
		
    	table.table.is-vcentered tr td {
            vertical-align: middle;
        }

		button.button.is-text, a.button.is-text {
        	text-decoration: none;
        }
    </style>
    @yield('extra_style')
    @yield('head_script')
</head>
<body style="background: #d1d1d1;">
	<div id="app">
		@include('partials.navigation')
		<div class="middle">
			<div class="columns is-gapless">
				<div class="column is-2">
					<div style="padding: 0.5rem 0.75rem;">
						@php
							$sidebarView = 'partials.sidebar';

							if (Str::contains(request()->route()->getPrefix(), 'student')) {
								$sidebarView = 'partials.sidebar-student';
							}
						@endphp
						@include($sidebarView)
					</div>
				</div>
				<div class="column" style="background: #ffffff">
					<div style="padding: 1rem; min-height: 630px;">
						@include('partials.status')

						@yield('content')
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="bottom"></div> -->

		{{-- MODAL DIALOG --}}
		@yield('modal')
	</div>

	<script>
		window.addEventListener('resize', function () {
			var myLogo = document.getElementById('my-logo');
			myLogo.style.display = document.documentElement.clientWidth <= 1088 ? 'none' : 'block';

			var sidebar = document.getElementById('sidebar');
			sidebar.style.position = document.documentElement.clientWidth <= 768 ? 'relative' : 'fixed';
		})
	</script>
	@yield('status_script')
	@yield('modal_script')
	@yield('extra_script')
</body>
</html>