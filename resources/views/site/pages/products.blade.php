@extends('layouts.site')

@section('content')
	<div id="produtos">Produtos</div>
	<hr/>
	<div>
		{{ r($product->toArray()) }}
	</div>
@endsection