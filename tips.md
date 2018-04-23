# Laravel Tips and Codes

## Composer

### Composer commands

```bash
composer clearcache
composer create-project marceloxp/laravel:@dev www
```

## Artisan

> Common commands

### Encryption Key Generate

```bash
php artisan key:generate
```

### Tinker Console

```bash
php artisan tinker
```

### Refresh autoloads

```bash
composer dump-autoload
```

## Artisan Database

> Database routines

### Refresh database and seed

```bash
php artisan migrate:refresh --seed
```

### Refresh database and seed in another env

```bash
php artisan migrate:refresh --seed --env=homolog
```

### Get current migration status

```bash
php artisan migrate:status
```

### Create controllers with defaults funcitons

```bash
php artisan make:controller AdminController -r
php artisan make:controller Admin\AdminController -r
```

### Create controllers without defaults funcitons

```bash
php artisan make:controller Admin\DashboardController
```

### Create a new Model

```bash
php artisan make:model Models/Leader
```

### Create a new Model and Migrate

```bash
php artisan make:model Models/State -m
```

### Create a new seeder

```bash
php artisan make:seed UsersSeeder
```

### Execute a particular seed

```bash
php artisan db:seed --class=StatesSeeder
```

## Migrations

### Create Migration

```bash
php artisan make:migration create_configs
```

### Alter Table

```bash
php artisan make:migration add_flags_to_config --table=prx_config
```

### Rollback last migration

```bash
php artisan migrate:rollback --step=1
```

### Rollback migrations (preview)

```bash
php artisan migrate:rollback --pretend
```