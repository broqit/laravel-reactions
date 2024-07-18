<?php

namespace Broqit\Laravel\Reactions;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Broqit\Laravel\Reactions\Http\Livewire\ReactionButton;

class ReactionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/reactions.php', 'reactions');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'reactions');

        $this->publishes([
            __DIR__.'/../config/reactions.php' => config_path('reactions.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/reactions'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/views/css' => public_path('vendor/reactions/css'),
        ], 'public');

        Livewire::component('reaction-button', ReactionButton::class);
    }
}
