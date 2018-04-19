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
			{{ r($caches) }}
		</div>
	</div>
@endsection
