# Laravel Boilerplate

> Basic site boilerplate start.
> Version 0.0.4

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

- Configure .env database config and run migration:

```bash
php artisan migrate:refresh --seed
```

- Add `.env` on `.gitignore`

## Framework

- Laravel 5.5.x: <https://laravel.com/>

## Plugins

- esemve/Hook: <https://github.com/esemve/Hook>
- digitalnature/php-ref: <https://github.com/digitalnature/php-ref>

## Libraries

- cjsbaseclass: <https://www.npmjs.com/package/cjsbaseclass>

## Admin Template

- AdminLTE: <https://adminlte.io/themes/AdminLTE/>

## Artisan commands

```bash
php artisan check
php artisan checkadmin
```

## Custom Classes

### Datasite

```php
Datasite::add('csrf_token', csrf_token());
Datasite::add(compact('url'));
```

### Cached

```php
Cached::get('brasil', 'states', $states, 10);
Cached::get('brasil', 'regions', $regions, 10);
Cached::forget('admin', 'states');
Cached::forget('admin'); // Clear all files on admin prefix
Cached::flush(); // Clear all cache
```

### MetaSocial

```php
MetaSocial::append('title', ' - Fale Conosco');
MetaSocial::set('description', 'Entre em contato conosco.');

Metasocial::print()
```

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

### Custom configs

- colors.php
- brasil.php (estados)
- admin.php (menu)

### Automatic Assets

- Javascripts and Stylesheets with same route name.

### Language (pt-br)

- See: `\resources\lang\pt-br\` files.

### Site pages structure

- Layout: `\resources\views\layouts`
- Pages: `\resources\views\site`

### Logs Folder

- `\storage\logs`