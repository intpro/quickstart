<?php

namespace Interpro\QuickStorage;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\ServiceProvider;

class QuickStorageServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        //Publishes package config file to applications config folder
        $this->publishes([__DIR__.'/Laravel/config/ersatzstorage.php' => config_path('ersatzstorage.php')]);

        $this->publishes([
            __DIR__.'/Laravel/migrations' => $this->app->databasePath().'/migrations'
        ], 'migrations');

        //Карта для поиска хэндлеров команд
        $dispatcher->maps([
            'Interpro\QuickStorage\Concept\Command\CreateGroupItemCommand' => 'Interpro\QuickStorage\Laravel\Handle\CreateGroupItemCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\UpdateGroupItemCommand' => 'Interpro\QuickStorage\Laravel\Handle\UpdateGroupItemCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\DeleteGroupItemCommand' => 'Interpro\QuickStorage\Laravel\Handle\DeleteGroupItemCommandHandler@handle'
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Let Laravel Ioc Container know about our Controller
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminCreateController');
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminUpdateController');
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminDeleteController');

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\Repository',
            'Interpro\QuickStorage\Laravel\EloquentRepository'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\StorageStructure',
            'Interpro\QuickStorage\Laravel\StorageStructure'
        );

        include __DIR__ . '/Laravel/Http/routes.php';
    }

}
