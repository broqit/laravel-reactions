# Laravel Reactions

A Laravel package for adding reactions to any model using Livewire.

## Installation

```bash
composer require broqit/laravel-reactions
```

## Database Migration

```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider" --tag=migrations
```

Run database migrations:

```bash
php artisan migrate
```

## Vendor publishing
```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider"
```

## Configuration
You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider" --tag="config"
```

## Usage

Add the HasReactions trait to your model:

```php
use YourNamespace\Reactions\Traits\HasReactions;

class Post extends Model
{
    use HasReactions;

    // Your model code
}
```

Add the Livewire component to your view:

```bladehtml
<livewire:reaction-button :model="$post" />
```
