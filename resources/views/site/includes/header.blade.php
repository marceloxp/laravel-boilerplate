<h1>Header</h1>
<hr/>
<div>
	@if ($customer->logged())
		{{ $customer->get('name') }}
		{{ $customer->logged() }}
	@else
		Usuário não logado
	@endif
</div>