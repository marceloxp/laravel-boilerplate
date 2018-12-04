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

	<h1>Cadastro</h1>

	@php
		$messages = Session::pull('message');
	@endphp

	@if($messages)
		<div class="alert alert-success" role="alert">
			{{ $messages }}
		</div>
	@endif

	<div class="container-fluid">
		{!! Form::open(['url' => url()->full(), 'method' => 'POST']) !!}
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('name') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('username') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('born') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('cpf') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('email') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('phone_prefix') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('phone') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('cep') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('state') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('city') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('address_type_id') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('address') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('address_number') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $cliente->input('complement') !!}
				</div>
				<div class="col-md-6">
					{!! $cliente->input('neighborhood') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6 form-check">
					{!! $cliente->input('status') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6 form-check">
					{!! $cliente->input('newsletter') !!}
				</div>
				<div class="col-md-6 form-check">
					{!! $cliente->input('rules') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					{{ Form::submit('Submit Form', ['class' => 'btn btn-primary']) }}
				</div>
			</div>
		{!! Form::close() !!}
	</div>

@endsection