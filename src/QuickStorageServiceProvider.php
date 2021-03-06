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
        $this->publishes([__DIR__.'/Laravel/config/qstorage.php' => config_path('qstorage.php')]);

        $this->publishes([
            __DIR__.'/Laravel/migrations' => $this->app->databasePath().'/migrations'
        ], 'migrations');

        //Карта для поиска хэндлеров команд
        $dispatcher->maps([
            'Interpro\QuickStorage\Concept\Command\CreateGroupItemCommand' => 'Interpro\QuickStorage\Laravel\Handle\CreateGroupItemCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\UpdateGroupItemCommand' => 'Interpro\QuickStorage\Laravel\Handle\UpdateGroupItemCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\DeleteGroupItemCommand' => 'Interpro\QuickStorage\Laravel\Handle\DeleteGroupItemCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\ReinitGroupCommand'     => 'Interpro\QuickStorage\Laravel\Handle\ReinitGroupCommandHandler@handle',

            'Interpro\QuickStorage\Concept\Command\InitAllBlockCommand' => 'Interpro\QuickStorage\Laravel\Handle\InitAllBlockCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\InitOneBlockCommand' => 'Interpro\QuickStorage\Laravel\Handle\InitOneBlockCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\UpdateBlockCommand'  => 'Interpro\QuickStorage\Laravel\Handle\UpdateBlockCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\ReinitOneBlockCommand'  => 'Interpro\QuickStorage\Laravel\Handle\ReinitOneBlockCommandHandler@handle',


            'Interpro\QuickStorage\Concept\Command\Image\RefreshAllGroupImageCommand' => 'Interpro\QuickStorage\Laravel\Handle\Image\RefreshAllGroupImageCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Image\RefreshBlockImageCommand'    => 'Interpro\QuickStorage\Laravel\Handle\Image\RefreshBlockImageCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Image\RefreshOneGroupImageCommand' => 'Interpro\QuickStorage\Laravel\Handle\Image\RefreshOneGroupImageCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Image\UpdateAllGroupImageCommand'  => 'Interpro\QuickStorage\Laravel\Handle\Image\UpdateAllGroupImageCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Image\UpdateBlockImageCommand'     => 'Interpro\QuickStorage\Laravel\Handle\Image\UpdateBlockImageCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Image\UpdateOneGroupImageCommand'  => 'Interpro\QuickStorage\Laravel\Handle\Image\UpdateOneGroupImageCommandHandler@handle',

            'Interpro\QuickStorage\Concept\Command\Crop\InitAllBlockCropCommand'     => 'Interpro\QuickStorage\Laravel\Handle\Crop\InitAllBlockCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\InitGroupCropCommand'        => 'Interpro\QuickStorage\Laravel\Handle\Crop\InitGroupCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\InitOneBlockCropCommand'     => 'Interpro\QuickStorage\Laravel\Handle\Crop\InitOneBlockCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\InitOneGroupCropCommand'     => 'Interpro\QuickStorage\Laravel\Handle\Crop\InitOneGroupCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\RefreshAllGroupCropCommand'  => 'Interpro\QuickStorage\Laravel\Handle\Crop\RefreshAllGroupCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\RefreshBlockCropCommand'     => 'Interpro\QuickStorage\Laravel\Handle\Crop\RefreshBlockCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\RefreshOneGroupCropCommand'  => 'Interpro\QuickStorage\Laravel\Handle\Crop\RefreshOneGroupCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\UpdateBlockCropCommand'      => 'Interpro\QuickStorage\Laravel\Handle\Crop\UpdateBlockCropCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Crop\UpdateOneGroupCropCommand'   => 'Interpro\QuickStorage\Laravel\Handle\Crop\UpdateOneGroupCropCommandHandler@handle',

            'Interpro\QuickStorage\Concept\Command\Flat\UpdateBlockFlatCommand'      => 'Interpro\QuickStorage\Laravel\Handle\Flat\UpdateBlockFlatCommandHandler@handle',
            'Interpro\QuickStorage\Concept\Command\Flat\UpdateGroupItemFlatCommand'  => 'Interpro\QuickStorage\Laravel\Handle\Flat\UpdateGroupItemFlatCommandHandler@handle'
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Interpro\QuickStorage\Concept\QSource',
            'Interpro\QuickStorage\Laravel\QSource'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\FieldProviding\FieldExtMediator',
            'Interpro\QuickStorage\Laravel\FieldProviding\FieldExtMediator'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\FieldProviding\FieldSaveMediator',
            'Interpro\QuickStorage\Laravel\FieldProviding\FieldSaveMediator'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\Repository',
            'Interpro\QuickStorage\Laravel\Repository'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\CropRepository',
            'Interpro\QuickStorage\Laravel\CropRepository'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\ImageRepository',
            'Interpro\QuickStorage\Laravel\ImageRepository'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\StorageStructure',
            'Interpro\QuickStorage\Laravel\StorageStructure'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\Sorting\GroupSortingSet',
            'Interpro\QuickStorage\Laravel\Sorting\GroupSortingSet'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\Specification\GroupSpecificationSet',
            'Interpro\QuickStorage\Laravel\Specification\GroupSpecificationSet'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\Param\GroupParam',
            'Interpro\QuickStorage\Laravel\Param\GroupParam'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\QueryAgent',
            'Interpro\QuickStorage\Laravel\QueryAgent'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\PaginalQueryAgent',
            'Interpro\QuickStorage\Laravel\PaginalQueryAgent'
        );

        $this->app->singleton(
            'Interpro\QuickStorage\Concept\JSONQueryAgent',
            'Interpro\QuickStorage\Laravel\JSONQueryAgent'
        );

        // Let Laravel Ioc Container know about our Controller
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminCreateController');
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminUpdateController');
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminDeleteController');
        $this->app->make('Interpro\QuickStorage\Laravel\Http\ImageFileController');
        $this->app->make('Interpro\QuickStorage\Laravel\Http\AdminCropImageController');

        include __DIR__ . '/Laravel/Http/routes.php';
    }

}
