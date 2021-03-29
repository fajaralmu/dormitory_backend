@extends('layouts.app')

@section('head_script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML'></script>
    <script src="{{ asset('js/socket.io.js') }}"></script>
@endsection

@section('content')
	<h1 class="title">CBT <span class="tag is-dark">run</span></h1>

	<soal></soal>
@endsection

@section('extra_script')
	<script src="{{ mix('js/cbt.js') }}"></script>
@endsection