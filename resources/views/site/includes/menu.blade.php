@if(config('app.multilanguague'))
	<ul>
		@foreach (config('app.all_langs') as $lang)
			@if ($lang != config('app.current_locale'))
				<li><a href="{{ lang_home_link($lang) }}">{{ $lang }}</a></li>
			@endif
		@endforeach
	</ul>
@endif

<ul>
	<li>
		<a href="{{route('home')}}">{{ dic('Página Inicial') }}</a>
	</li>
	<li>
		<a href="{{route('faleconosco')}}">{{ dic('Fale Conosco') }}</a>
	</li>
	<li>
		<a href="{{route('sobre')}}">{{ dic('Sobre') }}</a>
		<ul>
			<li>
				<a href="{{route('sobre_empresa')}}">{{ dic('Empresa') }}</a>
			</li>
			<li>
				<a href="{{route('sobre_tradicao')}}">{{ dic('Tradição') }}</a>
			</li>
		</ul>
	</li>
</ul>