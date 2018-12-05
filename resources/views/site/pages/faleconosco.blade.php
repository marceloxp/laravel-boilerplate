@extends('layouts.site')

@section('content')
	<div id="home">Content of Fale Conosco</div>

	<div class="container-fluid">
		{!! Form::open(['url' => url()->full(), 'method' => 'POST']) !!}
			<div class="form-group row">
				<div class="col-md-6">
					{!! $contact->input('name') !!}
				</div>
				<div class="col-md-6">
					{!! $contact->input('subject') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $contact->input('state') !!}
				</div>
				<div class="col-md-6">
					{!! $contact->input('city') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{!! $contact->input('email') !!}
				</div>
				<div class="col-md-6">
					{!! $contact->input('phone') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					{!! $contact->input('message') !!}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					{{ Form::submit('Enviar', ['class' => 'btn btn-primary']) }}
				</div>
			</div>
		{!! Form::close() !!}
	</div>

@endsection