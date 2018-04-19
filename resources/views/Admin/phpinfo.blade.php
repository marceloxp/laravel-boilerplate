@extends('layouts.admin')

@section('content-header')
	
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	<style type='text/css'>
		a:link { background-color: inherit !important; }
		a.logo { background-color: #367fa9 !important; }
	</style>
	@php
		phpinfo();
	@endphp
@endsection

@section('scripts')
	@include('Admin.includes.scripts_index')
@endsection