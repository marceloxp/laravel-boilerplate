<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Selecione um Tema</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body no-padding">
		<ul class="users-list clearfix">
			@foreach($themes as $theme)
					@php
						$count = \App\Models\Meme::approved()->step1Theme($theme->id)->count();
						$color = theme_color_selected($theme, $selected_theme, $count);
					@endphp
				<li style="width: 14%">
					<div style="position: relative;">
						<small class="label pull-right bg-{{ $color }}" style="position: absolute; right: 0px;">{{ $count }}</small>
						<a href="{{ route('admin_fase1', ['theme_id' => $theme->slug]) }}">
							{!! theme_img_selected($theme, $selected_theme) !!}
						</a>
					</div>
					<a class="users-list-name" href="#">{{ $theme->name }}</a>
					<span class="users-list-date">
						{{ App\Http\Utilities\Carbex::fromString($theme->date_ini)->toBrDate() }}
						<br>
						a
						<br>
						{{ App\Http\Utilities\Carbex::fromString($theme->date_end)->toBrDate() }}
					</span>
				</li>
			@endforeach
		</ul>
	</div>
</div>