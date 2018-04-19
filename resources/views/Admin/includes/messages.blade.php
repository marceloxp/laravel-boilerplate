@if (\Session::has('messages'))
	<section class="content-header">
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<ul>
						@foreach (\Session::get('messages') as $message)
							<li>{{ $message }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</section>
@endif

@if ($errors->any())
	<section class="content-header">
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</section>
@endif