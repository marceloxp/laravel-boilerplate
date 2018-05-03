# Laravel Boilerplate

> Basic site boilerplate start.
> Version 0.0.12

## Install

Clear compose cache (if needs)

```bash
composer clearcache
```

Install Site Package

```bash
composer create-project marceloxp/laravel:@dev www --no-interaction
```

### File `.env` configuration

> Configure .env database config and run migration:

```bash
php artisan migrate:refresh --seed
```

## Framework

- Laravel 5.5.x: <https://laravel.com/>

## Plugins

- esemve/Hook: <https://github.com/esemve/Hook>
- digitalnature/php-ref: <https://github.com/digitalnature/php-ref>

## Libraries

| Local  | Library          | Site                                           | Description                          |
| ------ | ---------------- | ---------------------------------------------- | ------------------------------------ |
| Global | cjsbaseclass.js  | <https://www.npmjs.com/package/cjsbaseclass>   | Base JS class                        |
| Admin  | prism.js         | <http://prismjs.com/>                          | Syntax highlighter                   |
| Admin  | sweetalert.js    | <https://sweetalert.js.org/>                   | A beautiful replacement for messages |

## Admin Template

> AdminLTE: <https://adminlte.io/themes/AdminLTE/>

## Artisan commands

```bash
php artisan check
php artisan checkadmin
```

## Custom Classes

### Datasite `\App\Http\Umstudio\Datasite`

```php
Datasite::add('csrf_token', csrf_token());
Datasite::add(compact('url'));
```

### Cached `\App\Http\Umstudio\Cached`

```php
Cached::get('brasil', 'states', $states, 10); // Minutes
Cached::get('brasil', 'regions', $regions, 10);
Cached::forget('admin', 'states');
Cached::forget('admin'); // Clear all files on admin prefix
Cached::flush(); // Clear all cache
```

### MetaSocial `\App\Http\Umstudio\MetaSocial`

```php
MetaSocial::use('sobre');
MetaSocial::append('title', ' - Fale Conosco');
MetaSocial::set('description', 'Entre em contato conosco.');
Metasocial::print();
```

### HttpCurl `\App\Http\Umstudio\HttpCurl`

```php
$json_data = HttpCurl::json('https://viacep.com.br/ws/05415030/json/');
```

### Cep `\App\Http\Umstudio\Cep`

```php
$address = Cep::get('04045-004');      // Returns Adddress
$valid   = Cep:valid('04045-004');     // Returns true
$valid   = Cep:valid('5');             // Returns false
$masket  = Cep:mask('4045004');        // Returns '04045-004'
$masket  = Cep:toNumeric('04045-004'); // Returns 4045004
```

### Result `\App\Http\Umstudio\Result`

```php
return Result::success('Dados cadastrados com sucesso.');
return Result::success('Dados cadastrados com sucesso.', ['id': 396]);
return Result::error('Ocorreu um erro na gravação do registro');
return Result::cached('', { 'id': 1, 'uf': 'sp' });
return Result::undefined();   // Ocorreu um erro na solcitação.
return Result::invalid();     // Entrada de dados inválida.
return Result::exception($e); // Ocorreu um erro na solcitação.
```

### RouteLang `\App\Http\Umstudio\RouteLang`

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

| Hook                                   | Location      | Description  |
| -----------                            | ------------- | -------------|
| admin_index_search_fields_{table_name} | Index         | Fields in search Combobox |
| admin_index_sort_fields_{table_name}   | Index         | Fields in sort Combobox |
| admin_index_{table_name}_{field_name}  | Index         | Before print field on index table |
| admin_show_{table_name}_{field_name}   | Show          | Before print field on show register |
| admin_edit_{table_name}_{field_name}   | Add/Edit      | Before print field on add/edit register |

### Helpers

#### vasset

```html
<!-- Add host and app version -->
<img src="{{vasset('/img/logo.png')}}">
<img src="https://wwww.site.com.br/img/logo.png?v=0.0.2">
```

#### script and css

```php
// Add host and app version
script('/js/main.js');
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

echo lang_home_link(); // returns current language root url
echo lang_home_link('en'); // returns root url for language [en]
```

#### string

```php
echo str_mask('04045004', '##.###-###'); // Returns '04.045-004
echo str_mask('04045004', '#####-###');  // Returns '04045-004
echo str_plural_2_singular('corações');  // Returns 'coração';

echo str2bool('true');  // Returns true;
echo str2bool('false'); // Returns false;
echo str2bool('foo');   // Returns false;
```

### Custom configs

- colors.php
- brasil.php (estados)
- cep.php (faixas por estado)
- admin.php (menu)
- social.php (facebook, twitter, etc)

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