@extends('layouts.admin')

@section('content-header')
	{{$panel_title[0]}}
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	@include('Admin.includes.table-show')
@endsection

@section('scripts')
	
@endsection