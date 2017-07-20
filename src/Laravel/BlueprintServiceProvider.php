<?php

namespace In\Blueprint\Laravel;

use Illuminate\Support\ServiceProvider;
use In\Blueprint\Laravel\Command\GenerateCommand;

class BlueprintServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $viewPath = __DIR__ . '/../resources/views';
        $this->loadViewsFrom($viewPath, 'blueprint');

        // Publish a config file
        $configPath = __DIR__ . '/config/blueprint.php';
        $this->publishes([
            $configPath => config_path('blueprint.php'),
        ], 'config');

        //Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => config('blueprint.paths.views'),
        ], 'views');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/config/blueprint.php';
        $this->mergeConfigFrom($configPath, 'blueprint');

        //        $this->app->singleton('command.blueprint.publish', function () {
        //            return new PublishCommand();
        //        });
        //
        //        $this->app->singleton('command.blueprint.publish-config', function () {
        //            return new PublishConfigCommand();
        //        });
        //
        //        $this->app->singleton('command.blueprint.publish-views', function () {
        //            return new PublishViewsCommand();
        //        });

        $this->app->singleton('command.blueprint.generate', function () {
            return new GenerateCommand();
        });

        $this->commands(
        //            'command.blueprint.publish',
        //            'command.blueprint.publish-config',
        //            'command.blueprint.publish-views',
            'command.blueprint.generate'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.blueprint.publish',
            'command.blueprint.publish-config',
            'command.blueprint.publish-views',
            'command.blueprint.generate',
        ];
    }
}
