@extends('layouts.admin')

@section('styles')
	{{ css('/assets/jquery-treegrid/css/jquery.treegrid.css') }}
@endsection

@section('content-header')
	{{ $panel_title }}
@endsection

@section('content-description')
	@if (!empty($panel_description))
		<h4>
			{{ $panel_description }}
		</h4>
	@endif
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	@include('Admin.includes.tree')
@endsection

@section('scripts')
	@include('Admin.includes.scripts_tree')
@endsection