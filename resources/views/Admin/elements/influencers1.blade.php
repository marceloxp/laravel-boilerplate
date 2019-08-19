@if ($selected_theme)
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Selecione um Memestre</h3>
		</div>
		<div class="box-body no-padding">
			<ul class="users-list clearfix">
				@foreach($influencers as $influencer)
					@php
						$count = \App\Models\Meme::approved()->step1InfluencerTheme($influencer->id, $selected_theme->id)->count();
						$color = influencer_color_selected($influencer, $selected_influencer, $count);
					@endphp
					<li style="width: 25%">
						<div style="position: relative;">
							<small class="label pull-right bg-{{ $color }}" style="position: absolute; right: 0px;">{{ $count }}</small>
							<a href="{{ route('admin_fase1', ['theme_id' => $selected_theme->slug, 'influencer' => $influencer->slug]) }}">
								{!! influencer_img_selected($influencer, $selected_influencer) !!}
							</a>
						</div>
						<a class="users-list-name" href="#">{{ $influencer->nickname }}</a>
						<span class="users-list-date">
							{{ $influencer->name }}
						</span>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
@endif