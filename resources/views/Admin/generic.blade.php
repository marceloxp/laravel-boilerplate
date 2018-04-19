@extends('layouts.admin')

@section('content-header')
	{{$panel_title}}
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	@include('Admin.includes.table')
@endsection

@section('scripts')
	@include('Admin.includes.scripts_index')
@endsection