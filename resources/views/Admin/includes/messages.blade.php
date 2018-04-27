@if (\Session::has('messages'))
	<div style="display: none">
		<div id="message-success">
			<table class="table table-bordered table-condensed table-hover table-striped">
				@foreach (\Session::get('messages') as $message)
					<tr><td>{{ $message }}</td></tr>
				@endforeach
			</table>
		</div>
	</div>
	<script>
		swal({'title': 'Atenção!', 'content': document.getElementById('message-success'), 'icon': 'success', 'timer': 10000});
	</script>
@endif

@if ($errors->any())
	<div style="display: none">
		<div id="message-error">
			<table class="table table-bordered table-condensed table-hover table-striped">
				@foreach ($errors->all() as $error)
					<tr><td>{{ $error }}</td></tr>
				@endforeach
			</table>
		</div>
	</div>
	<script>
		swal({'title': 'Atenção!', 'content': document.getElementById('message-error'), 'icon': 'error', 'timer': 10000});
	</script>
@endif