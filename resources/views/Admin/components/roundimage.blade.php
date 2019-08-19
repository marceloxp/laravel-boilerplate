<li class="{{ $li_class }}" style="width: {{ $imagewidth }}%" {!! $data !!}>
	<div style="position: relative;">
		@if ($clickable)
			<a href="#">
				{!! $image !!}
			</a>
		@else
			{!! $image !!}
		@endif
	</div>
	@if ($caption)
		<a class="users-list-name" href="#">{{ $caption }}</a>
	@endif
</li>
