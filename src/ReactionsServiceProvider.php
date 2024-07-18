<?php

namespace Broqit\Laravel\Reactions;

use Illuminate\Support\ServiceProvider;

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
            __DIR__.'/../resources/views' => resource_path('views/vendor/reactions'),
        ], 'reactions-config');
    }
}
