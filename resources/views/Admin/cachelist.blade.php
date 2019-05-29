@extends('layouts.admin')

@section('content-header')
	{!! admin_breadcrumb(['Home', 'Cache List'], 'fa fa-list') !!}
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')

<div class="box-body">
	<div class="box-group" id="accordion">
		@php $index = 1; @endphp
		@foreach ($caches as $prefix => $cache)
			<div class="panel box box-primary">
				<div class="box-header with-border">
					<h4 class="box-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $index }}" aria-expanded="false" class="">
							{{ $prefix }}
						</a>
					</h4>
				</div>
				<div id="collapse{{ $index }}" class="panel-collapse collapse" style="">
					<div class="box-body">
						<pre><code class="language-json line-numbers">{{ json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
					</div>
				</div>
			</div>
			@php $index++; @endphp
		@endforeach
	</div>
</div>

@endsection

@section('styles')
	{{ css('/admin-lte-custom/css/prism.css') }}
@endsection

@section('scripts')
	{{ javascript('/admin-lte-custom/js/prism.js') }}
@endsection