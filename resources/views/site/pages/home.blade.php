@extends('layouts.site')

@section('head')
	@parent
	<meta name="my-custom-meta" content="my-custom-content in home">
@endsection

@section('header')
	<h1>Before Header</h1>
	@parent
	<h1>After Header</h1>
@endsection

@section('menu')
	<p>Before Menu</p>
	@parent
	<p>After Menu</p>
@endsection

@section('footer')
	<p>Before Footer</p>
	@parent
	<p>After Footer</p>
@endsection

@section('content')
	<div id="home">Content of home page</div>

	<div style="padding: 20px; background-color: silver; width: 320px;">
		{{ img('/images/laravel-logo.png', 'width="300"') }}
	</div>
@endsection