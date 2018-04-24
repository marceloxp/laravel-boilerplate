@extends('layouts.admin')

@section('content-header')
	Cache Config
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<form role="form">
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control" value="{{ $cache_count }}" readonly>
										<div class="input-group-btn">
											<a type="button" class="btn btn-danger" href="{{ route('admin_cache_clear') }}"><i class="fa fa-trash"></i> Limpar</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Utilizar Cache</label>
									<select class="form-control" id="usecache">
										<option value="s" {{ ($use_cache == 's') ? 'selected' : '' }} >Sim</option>
										<option value="n" {{ ($use_cache == 'n') ? 'selected' : '' }} >Não</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('styles')
	
@endsection

@section('scripts')
	
@endsection