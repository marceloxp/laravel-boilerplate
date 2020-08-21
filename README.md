# Laravel Boilerplate

> Basic site boilerplate start.
> Version 0.4.4

## Install

Clear compose cache (if needs)

```bash
composer clearcache
```

Install Site Package

```bash
composer create-project marceloxp/laravel www --no-interaction -s dev
```

### File `.env` configuration

> Configure .env database config and run migration

```bash
php artisan migrate:refresh --seed
```

## Configure cron job
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Framework

- Laravel 7.x: <https://laravel.com/>

### Server Requirements

- PHP >= 7.2.5
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

### Dev URL

> <http://www.local.laravel-boilerplate.com.br>

### Updates

## summernote

`composer.json`
```json
	"require": {
	    ...
		"summernote/summernote": "^0.8.8"
	},
	"scripts": {
		"post-update-cmd": [
			"php artisan vendor:publish --tag=summernote"
		],
		...
	}
```

`AppServiceProvider.php`
```php
	public function boot()
	{
        // ...
		$publishes = [ base_path('vendor/summernote/summernote/dist') => public_path('vendor/summernote') ];
		$this->publishes($publishes, 'summernote');
	}
```

## Plugins

| Plugin                           | Address                                                  |
| -------------------------------- | -------------------------------------------------------- |
| shridharkaushik29/laravel-hooks  | <https://github.com/shridharkaushik29/laravel-hooks>     |
| digitalnature/php-ref            | <https://github.com/digitalnature/php-ref>               |
| barryvdh/laravel-debugbar        | <https://github.com/barryvdh/laravel-debugbar>           |
| marceloxp/laravel_commands       | <https://github.com/marceloxp/laravel_commands>          |
| webreinvent/laravel-nestable     | <https://github.com/atayahmet/laravel-nestable>          |
| ezyang/htmlpurifier              | <https://github.com/ezyang/htmlpurifier>                 |
| laravelcollective/html           | <https://github.com/LaravelCollective/html>              |
| summernote/summernote            | <https://github.com/summernote/summernote>               |
| technoknol/log-my-queries        | <https://github.com/technoknol/LogMyQueries>             |

## Libraries

| Local  | Library          | Site                                           | Description                                                                       |
| ------ | ---------------- | ---------------------------------------------- | --------------------------------------------------------------------------------- |
| Global | cjsbaseclass.js  | <https://www.npmjs.com/package/cjsbaseclass>   | Base JS class                                                                     |
| Admin  | prism.js         | <http://prismjs.com/>                          | Syntax highlighter                                                                |
| Admin  | sweetalert.js    | <https://sweetalert.js.org/>                   | A beautiful replacement for messages                                              |
| Admin  | RowSorter.js     | <https://github.com/arteyazilim/rowsorter/>    | Drag & drop table row sorter pluging with touch support for Vanilla JS and jQuery |

## Admin Template

> AdminLTE: <https://adminlte.io/themes/AdminLTE/>

## Custom Classes

### Datasite `\App\Http\Utilities\Datasite`

```php
Datasite::add('csrf_token', csrf_token());
Datasite::add(compact('url'));

datasite_add('csrf_token', csrf_token());
datasite_add(compact('url'));
```

### Cached `\App\Http\Utilities\Cached`

```php
// Time in minutes
Cached::get('brasil', 'states', $states, 10);
Cached::get('brasil', 'regions', $regions, 10);
$result = Cached::get
(
	'group_name',
	['name_1', 'name_2'],
	function() use ($args)
	{
		return \App\Models\Category::get()->first();
	},
	5
);
Cached::forget('admin', 'states');
Cached::forget('admin'); // Clear all files on admin prefix
Cached::flush();         // Clear all cache
```

### MetaSocial `\App\Http\Utilities\MetaSocial`

```php
MetaSocial::use('sobre');
MetaSocial::append('title', ' - Fale Conosco');
MetaSocial::set('description', 'Entre em contato conosco.');
Metasocial::print();
```

### HttpCurl `\App\Http\Utilities\HttpCurl`

```php
$json_data = HttpCurl::json('https://viacep.com.br/ws/05415030/json/');
```

### Cep `\App\Http\Utilities\Cep`

```php
$address = Cep::get('04045-004');      // Returns Adddress
$valid   = Cep:valid('04045-004');     // Returns true
$valid   = Cep:valid('5');             // Returns false
$masket  = Cep:mask('4045004');        // Returns '04045-004'
$masket  = Cep:toNumeric('04045-004'); // Returns 4045004
```

### Result `\App\Http\Utilities\Result`

```php
return Result::success('Dados cadastrados com sucesso.');
return Result::success('Dados cadastrados com sucesso.', ['id': 396]);
return Result::error('Ocorreu um erro na gravação do registro');
return Result::cached('', { 'id': 1, 'uf': 'sp' });
return Result::undefined();   // Ocorreu um erro na solicitação.
return Result::invalid();     // Entrada de dados inválida.
return Result::exception($e); // Ocorreu um erro na solicitação.
```

### RouteLang `\App\Http\Utilities\RouteLang`

> Used in `/routes/multilanguague.php`

```php
RouteLang::lang();                    // returns current language, string empty if is default language (pt-br). Ex.: ''
RouteLang::lang('pt-br');             // returns current language, string empty if is default language. Ex.: ''
RouteLang::lang('en');                // returns current language, string empty if is default language. Ex.: 'en'
RouteLang::root();                    // returns current site root language
RouteLang::rootUrl();                 // returns current site full root url language
RouteLang::rootUrl('en');             // returns full root url to language [enb]
RouteLang::prefix('/sobre');          // Translate prefix to current language
RouteLang::route($route, '/empresa'); // Translate url route to current language
RouteLang::getDefaultLocale();        // Returns app default locale config
RouteLang::getCurrentLocale();        // Returns app current locale config (dynamic)
```

### Predefined API routes

- `{{url}}/api/brasil/states`
- `{{url}}/api/brasil/cities/rj`

### Hooks

| Hook                                              | Location      | Description                             |
| ------------------------------------------------- | ------------- | --------------------------------------- |
| admin_index_search_fields_{table_name}            | Index         | Fields in search Combobox               |
| admin_index_sort_fields_{table_name}              | Index         | Fields in sort Combobox                 |
| admin_index_{table_name}_{field_name}             | Index         | Before print field on index table       |
| admin_index_title_align_{table_name}_{field_name} | Index         | Define grid title alignment             |
| admin_index_field_align_{table_name}_{field_name} | Index         | Define grid field record alignment      |
| admin_show_{table_name}_{field_name}              | Show          | Before print field on show register     |
| admin_edit_{table_name}_{field_name}              | Add/Edit      | Before print field on add/edit register |
| master_model_field_type_{table_name}_{field_name} | Master Model  | Before get field type register          |

### Helpers

#### admin

```php
// ['inativo','não','i','n','no','0','excluido'])) ? 'red' : 'green';
echo admin_label_status($value);

// bootstrap badge
echo admin_badge_status($value);

// ['RJ' => 'Rio de Janeiro', 'SP' => 'São Paulo']
echo admin_select($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select = false)

// ['RJ', 'SP']
echo admin_select_simple($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select = false)

echo admin_select_simple_with_add_button($p_field_name, $p_options, $p_field_value, $p_required, $p_add_text_select = false)
```

#### Bootstrap

```php
echo alert_success('Mensagem enviada com sucesso.');
echo alert_danger('Ocorreu um erro na solicitação!');
echo print_alert(); // Auto print messages from Session
```

#### Money
```sh
>>> use \App\Http\Utilities\Money;

## Basic ------------------------------------------------
>>> $var = new Money(2.5);
=> App\Http\Utilities\Money {
	 +value: 2.5,
	 +formated: "2,50",
   }
>>> $var->value;
=> 2.5
>>> $var->formated;
=> "2,50"
>>> $var->getRaw();
=> "250"

## Increment --------------------------------------------
>>> $var->inc(3.50);
=> App\Http\Utilities\Money {
	 +value: 6.0,
	 +formated: "6,00",
   }
   
## Create another Money object
>>> $another = new Money(1.00);
=> App\Http\Utilities\Money {
	 +value: 1.0,
	 +formated: "1,00",
   }
   
## Increment using Money Object -------------------------
>>> $var->inc($another);
=> App\Http\Utilities\Money {
	 +value: 7.0,
	 +formated: "7,00",
   }
```

#### DB

```php
echo db_database_name();                                // Returns current database name
echo db_comment_table('table_name', 'comment_table');   // Define table comment
echo db_get_comment_table('table_name');                // Returns table comment
echo db_get_pivot_table_name(['videos','tags'], true);  // Returns pivot table name (Ex: blp_tag_video)
echo db_get_pivot_scope_name([Model1, Model2]);         // Returns a pivot scope name (Ex: db_get_pivot_scope_name([Video::class, Tag::class]) => tagVideo)
echo db_get_primary_key('table_name');                  // Returns id
echo db_get_name('table_name', 10);                     // Returns `name` field value
echo db_select_one(Model, ['fields'], ['where'], true); // Returns only one register (Ex: echo db_select_one(\App\Models\City::class, ['id','name'], ['name' => 'São Paulo'], true) => {"id":5325,"name":"São Paulo"})
echo db_select_id(Model, ['where'], false);             // Returns only if by where (Ex: echo db_select_id(\App\Models\City::class, ['name' => 'São Paulo'], true) => 5325)
echo db_model_to_table_name('City');                    // Returns table name from model name => cities
echo db_table_name_to_model('cities');                  // Returns model name from table name => City
echo db_table_name_to_model_path('cities');             // Returns path model from table name => \App\Models\City
echo db_table_name_to_field_id('cities');               // Returns relative field id to another table => city_id
echo db_table_exists('cities');                         // Returns if table exists in database
```

#### vasset

```html
<!-- Versioned Asset -->
<!-- Add host and app version -->
<img src="{{ vasset('/img/logo.png') }}">
<img src="https://wwww.site.com.br/img/logo.png?v=0.0.2">
```

#### javascript and css

```php
// Add host and app version
javascript('/js/main.js');
<script type="text/javascript" src="https://wwww.site.com.br/js/main.js?v=0.0.1"></script>>

css('/css/style.css');
<link rel="stylesheet" type="text/css" href="https://wwww.site.com.br/css/style.css?v=0.0.1">
```

#### app_version

```php
app_version('0.0.3')
// returns version value from config/app.php or default.
```

#### cached_headers

```php
return response($result)->withHeaders(cached_headers($result));
```

#### developer

> Execute `dump` and `die`.

```php
ddd($var);
```

#### lang

> Prints variable in current language, defaults to parameter.

```php
echo dic('Página Inicial');
{{ dic('Página Inicial') }}

echo lang_home_link();     // returns current language root url
echo lang_home_link('en'); // returns root url for language [en]
```

#### string

```php
echo str_mask('04045004', '##.###-###'); // Returns '04.045-004'
echo str_mask('04045004', '#####-###');  // Returns '04045-004'
echo str_plural_2_singular('corações');  // Returns 'coração';

echo str2bool('true');  // Returns true;
echo str2bool('false'); // Returns false;
echo str2bool('foo');   // Returns false;
```

### Custom configs

| Config          | Description                    |
| --------------- | ------------------------------ |
| admin.php       | Menu                           |
| brasil.php      | Estados                        |
| cep.php         | Faixas de CEP por estado       |
| colors.php      | Bootstrap colors               |
| metasocial.php  | Headers metatags               |
| social.php      | Facebook, Twitter, etc         |
| hook.php        | On/Off Print Admin hooks       |
| codetrait.php   | Length of model uniq code      |
| tables.php      | Custom configs on admin tables |
| payment.php     | Payments Type                  |

### Automatic Assets

> Javascripts and Stylesheets with same route name.

| URL                              | Javascript            | CSS                    |
| -------------------------------- | --------------------- | ---------------------- |
| http://site.com.br/faleconosco   | /js/faleconosco.js    | /css/faleconosco.css   |
| http://site.com.br/sobre/empresa | /js/sobre_empresa.js  | /css/sobre_empresa.css |

### Language (pt-br)

- See: `\resources\lang\pt-br\` files.

### Site pages structure

- Layout: `\resources\views\layouts`
- Pages: `\resources\views\site`

### Logs Folder

- `\storage\logs`

### Custom Buttons on Admin Index

> On Controller Admin => function index, add:

```txt
admin_table_index_set_button('table_name', 'button_id', 'type', 'button_style', button_disabled, 'font-awesome-icon', 'Button Text', 'Confirm Message');
button_id         : HTML Element ID and Route
type              : many, one
button_style      : btn-default, btn-primary, btn-success, btn-info, btn-danger, btn-warning
button_disabled   : true, false
font-awesome-icon : <https://fontawesome.com/icons?d=gallery&m=free>
```

```php
// Example:
admin_table_index_set_button('users', 'btn-send-mail', 'many', 'btn-success', true, 'fas fa-envelope', 'Send Mail', 'Deseja enviar os e-mails para os registros selecionados?');

// Ajax Controller:
public function onUsersBtnSendMail(Request $request, $ids)
{
	return Result::success('Solicitação efetuada com sucesso.', $ids);
}
```

Route: <http://www.host.com.br/admin/ajax/{table}/{button_id}>
Controller: AjaxController
Action: Camel Case of table and button_id. Example AjaxController->onUsersBtnSendMail