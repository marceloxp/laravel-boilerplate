@extends('layouts.site')

@section('content')
	<div id="sobre_empresa">Content of Sobre a Empresa</div>
@endsection

@section('scripts')
	{{ script('/js/sobre.js') }}
	@parent
@endsection