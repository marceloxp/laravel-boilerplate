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
		<a href="{{ route('home') }}">{{ dic('Página Inicial') }}</a>
	</li>
	<li>
		<a href="{{ route('faleconosco') }}">{{ dic('Fale Conosco') }}</a>
	</li>
	<li>
		<a href="{{ route('produtos') }}">{{ dic('Produtos') }}</a>
	</li>
	<li>
		<a href="{{ route('usuario_cadastro') }}">{{ dic('Cadastro') }}</a>
	</li>
	<li>
		<a href="{{ route('sobre') }}">{{ dic('Sobre') }}</a>
		<ul>
			<li>
				<a href="{{ route('sobre_empresa') }}">{{ dic('Empresa') }}</a>
			</li>
			<li>
				<a href="{{ route('sobre_tradicao') }}">{{ dic('Tradição') }}</a>
			</li>
		</ul>
	</li>
	<li>
		@if($customer->logged())
			<a href="{{ route('usuario_logout') }}">{{ dic('Logout') }}</a>
		@else
			<a href="{{ route('usuario_login') }}">{{ dic('Login') }}</a>
		@endif
	</li>
</ul>