@extends('layouts.site')

@section('content')

	<h1>Login</h1>

	<div class="container-fluid">
		{!! Form::open(['url' => url()->full(), 'method' => 'POST']) !!}
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('email', 'E-Mail') }}
					{{ Form::text('email', null, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('password', 'Senha') }}
					{{ Form::password('password', ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					{{ Form::submit('Login', ['class' => 'btn btn-primary']) }}
				</div>
			</div>
		{!! Form::close() !!}
	</div>

	@if($errors->any())
		<div class="alert alert-danger" role="alert">
			{{ $errors->first() }}
		</div>
	@endif

@endsection