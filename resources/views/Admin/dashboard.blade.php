@extends('layouts.admin')

@section('content-header')
	Dashboard
@endsection

@section('sidebar')
	@include('Admin.includes.sidebar')
@endsection

@section('content')
	<div class="row">
		@foreach ($counts as $item)
			@if($item['visible'])
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon {{$item['color']}}"><a href="{{$item['link']}}" style="color: white; cursor: pointer;"><i class="fa {{$item['ico']}}"></i></span></a>
						<div class="info-box-content">
							<span class="info-box-text">{{$item['caption']}}</span>
							<span class="info-box-number">{{$item['quant']}}</span>
						</div>
					</div>
				</div>
			@endif
		@endforeach
	</div>
@endsection