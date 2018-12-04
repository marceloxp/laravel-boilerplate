<h1>Header</h1>
<hr/>
<div class="alert alert-primary" role="alert">
	@if ($customer->logged())
		{{ $customer->get('name') }}
	@else
		Usuário não logado
	@endif
</div>