# Laravel Reactions

A Laravel plugin that allows users to leave reactions on posts using Livewire, similar to the functionality in `spatie/laravel-comments`.

![Laravel Reactions Plugin](https://i.ibb.co/Vtwb6h5/2024-07-18-22-37-49.png)

## Features
- Flexible Reaction Types: Define multiple reaction types with custom names and icons through the configuration file.
- User and Guest Reactions: Allow both authenticated users and guests to leave reactions. Configurable to restrict reactions to only users, only guests, or both.
- Customizable Reaction Limits: Set the maximum number of reactions a user or guest can leave on a single post through the configuration file.
- Dynamic Reaction Display: Utilize a Livewire component to dynamically display reaction buttons with real-time updates.
- Remove Reactions: Users and guests can remove their reactions. Configurable to set a time limit within which reactions can be removed.
- Reaction Count Display: Display the count of each reaction type for a given post.
- Total Reaction Count: Retrieve the total count of reactions for a given post.
- Grouped Reaction Count: Retrieve a count of all reactions for a given post, grouped by reaction type.
- Custom User Model: Easily configure the user model to be used for reactions.
- Easy Integration: Simple integration with any Laravel model using the HasReactions trait.

### Example configuration for reaction types:
```php
return [
    'types' => [
        ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
        ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
        ['type' => 'haha', 'name' => 'Haha', 'icon' => '😂'],
        ['type' => 'wow', 'name' => 'Wow', 'icon' => '😮'],
        ['type' => 'sad', 'name' => 'Sad', 'icon' => '😢'],
        ['type' => 'angry', 'name' => 'Angry', 'icon' => '😡'],
    ],
    'allowed_users' => ['user', 'guest'], // Possible values: 'user', 'guest', 'both'
    'max_reactions_per_user' => 1,
    'table_name' => 'custom_reactions', // Table name
    'user_model' => null, // Default user model - null
    'removal_window_hours' => null, // Number of hours within which reactions can be removed, null means no limit
];
```

## Installation

```bash
composer require broqit/laravel-reactions
```

### Database Migration

```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider" --tag=migrations
php artisan migrate
```

### Vendor publishing
```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider"
```

### Style publishing
```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider" --tag="public"
```

### Configuration
You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Broqit\Laravel\Reactions\ReactionsServiceProvider" --tag="config"
```

## Usage

Add the HasReactions trait to your model:

```php
use Broqit\Reactions\Traits\HasReactions;

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

Retrieve reaction counts:
```php
// Get the total count of reactions for a post
$totalReactions = $post->getTotalReactionsCount();

// Get the number of 'like' reactions for a post
$likeReactions = $post->getReactionsCountByType('like');

// Get the count of all reactions for a post, grouped by type
$groupedReactions = $post->getReactionsCountGroupedByType();
```