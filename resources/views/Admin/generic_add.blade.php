@extends('layouts.admin')

@section('content-header')
	{{ $panel_title[0] }}
@endsection

@section('styles')
	{{ css('/admin-lte-custom/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css') }}
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	@include('Admin.includes.table-edit')
@endsection

@section('scripts')
	@include('Admin.includes.scripts_edit')
	{{ javascript('/js/admin/admin_' . $table_name . '_form.js') }}
@endsection