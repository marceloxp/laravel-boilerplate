@extends('layouts.admin')

@section('content-header')
	Cache List
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			@foreach ($caches as $prefix => $cache)
				<h4>{{ $prefix }}</h4>
				<pre><code class="language-json line-numbers">{{ json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
			@endforeach
		</div>
	</div>
@endsection

@section('styles')
	{!! css('/admin-lte-custom/css/prism.css') !!}
@endsection

@section('scripts')
	{!! script('/admin-lte-custom/js/prism.js') !!}
@endsection