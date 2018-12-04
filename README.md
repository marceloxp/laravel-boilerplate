# Laravel Boilerplate

> Basic site boilerplate start.
> Version 0.1.8

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

## Framework

- Laravel 5.7.x: <https://laravel.com/>

### Server Requirements

- PHP >= 7.1.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension

### Dev URL

> http://www.local.laravel-boilerplate.com.br

## Plugins

| Plugin                           | Address                                                  |
| -------------------------------- | -------------------------------------------------------- |
| shridharkaushik29/laravel-hooks  | <https://github.com/shridharkaushik29/laravel-hooks>     |
| digitalnature/php-ref            | <https://github.com/digitalnature/php-ref>               |
| barryvdh/laravel-debugbar        | <https://github.com/barryvdh/laravel-debugbar>           |
| miroc/laravel-adminer            | <https://github.com/miroc/Laravel-Adminer>               |
| chumper/zipper                   | <https://github.com/Chumper/Zipper>                      |
| marceloxp/laravel_commands       | <https://github.com/marceloxp/laravel_commands>          |
| danielstjules/stringy            | <https://github.com/danielstjules/Stringy>               |

## Libraries

| Local  | Library          | Site                                           | Description                          |
| ------ | ---------------- | ---------------------------------------------- | ------------------------------------ |
| Global | cjsbaseclass.js  | <https://www.npmjs.com/package/cjsbaseclass>   | Base JS class                        |
| Admin  | prism.js         | <http://prismjs.com/>                          | Syntax highlighter                   |
| Admin  | sweetalert.js    | <https://sweetalert.js.org/>                   | A beautiful replacement for messages |

## Admin Template

> AdminLTE: <https://adminlte.io/themes/AdminLTE/>

## Artisan commands

> Create a new Admin Controller

```bash
php artisan makex:admin_controller
```

> Create a new Single Master Model

```bash
php artisan makex:mastermodel
```

> Update framework core files

```bash
php artisan makex:updatecore
```

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
Cached::get('brasil', 'states', $states, 10);   // Minutes
Cached::get('brasil', 'regions', $regions, 10);
Cached::forget('admin', 'states');
Cached::forget('admin');                        // Clear all files on admin prefix
Cached::flush();                                // Clear all cache
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
return Result::undefined();   // Ocorreu um erro na solcitação.
return Result::invalid();     // Entrada de dados inválida.
return Result::exception($e); // Ocorreu um erro na solcitação.
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

#### DB

```php
echo db_comment_table('table_name', 'comment_table'); // Define table comment
echo db_get_primary_key('table_name');                // Returns id
echo db_get_name('table_name', 10);                   // Returns `name` field value
```

### Custom configs

| Config          | Description              |
| --------------- | ------------------------ |
| admin.php       | Menu                     |
| brasil.php      | Estados                  |
| cep.php         | Faixas de cep por estado |
| colors.php      | Bootstrap colors         |
| metasocial.php  | Headers metatags         |
| social.php      | Facebook, Twitter, etc   |

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