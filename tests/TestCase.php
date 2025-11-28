<?php

namespace Broqit\Laravel\Reactions\Tests;

use Broqit\Laravel\Reactions\ReactionsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            ReactionsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup APP_KEY for Livewire encryption
        $app['config']->set('app.key', 'base64:' . base64_encode(
            \Illuminate\Support\Str::random(32)
        ));

        // Setup session for Livewire
        $app['config']->set('session.driver', 'array');

        // Setup reactions config
        $app['config']->set('reactions.table_name', 'custom_reactions');
        $app['config']->set('reactions.allowed_users', 'both');
        $app['config']->set('reactions.max_reactions_per_user', 1);
        $app['config']->set('reactions.removal_window_hours', null);
    }
}

