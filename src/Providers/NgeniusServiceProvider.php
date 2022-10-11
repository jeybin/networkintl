<?php

namespace Jeybin\Networkintl\Providers;

use Illuminate\Support\ServiceProvider;
use Jeybin\Networkintl\Facades\NgeniusFacades;
use Jeybin\Networkintl\App\Console\CopyNgeniusWebhooks;
use Jeybin\Networkintl\App\Console\PublishNgeniusProviders;
use Jeybin\Networkintl\App\Console\CopyNgeniusMigrationFiles;

class NgeniusServiceProvider extends ServiceProvider
{   

    /***
     * Publish Service provider using
     *   php artisan ngenius:install 
     *   or 
     *   php artisan vendor:publish --provider=Jeybin\Networkintl\Providers\NgeniusServiceProvider
     * 
     * Copy migration files 
     *   php artisan vendor:publish 
     * 
     */


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * Autoloading the helper functions
         */
        require_once __DIR__ . '/../App/Helpers/ResponseHelper.php';


        /**
         * Path of the migration file of ngenius_gateway inside the composer package
         */
        $ngenius_gateway_package_path = __DIR__.'/../../database/migrations/create_ngenius_table.php.stub';

        /**
         * Migration file of ngenius_gateway path where it need to be copied
         */
        $ngenius_gateway_project_path = database_path("migrations/ngenius/create_ngenius_table.php");

        /**
         * Path of the migration file of ngenius_gateway_webhooks inside the composer package
         */
        $ngenius_gateway_webhook_package_path = __DIR__.'/../../database/migrations/create_ngenius_webhooks_table.php.stub';

        /**
         * Migration file of ngenius_gateway_webhooks path where it need to be copied
         */
        $ngenius_gateway_webhook_project_path = database_path("migrations/ngenius/create_ngenius_webhooks_table.php");

        /**
         * Migrations needed to be published,
         * can publish multiple files, add 
         * more into the array
         */
        $publishMigrations = [$ngenius_gateway_package_path =>$ngenius_gateway_project_path,
                              $ngenius_gateway_webhook_package_path =>$ngenius_gateway_webhook_project_path];

        /**
         * Publishes the Migrations files
         * with a tag name ngenius can use any tag 
         * name, use the same name while publishing the 
         * vendor 
         */
        $this->publishes($publishMigrations, 'ngenius-migrations');

        /**
         * Config file merging into the project
         */
        
        $configs = [
            __DIR__.'/../../Config/ngenius-config.php' => config_path('ngenius-config.php') 
        ];

        $this->publishes($configs, 'ngenius-config');



        /**
         * Publishing webhook jobs inside the folder NgeniusWebhooks
         * to App/Jobs/NgeniusWebhooks
         */
        $webhooks = [
            __DIR__.'/../../NgeniusWebhooks' => app_path('Jobs/NgeniusWebhooks') 
        ];
        $this->publishes($webhooks, 'ngenius-webhooks');



        /**
         * Adding the package routes to the project
         * Here we are adding the webhook listener api
         */
        $this->loadRoutesFrom(__DIR__.'/../Routes/ngenius-api.php');


        /**
         * Checking if the app is 
         * running from console
         */
        if ($this->app->runningInConsole()) {

            /**
             * Adding custom commands class to the 
             * service provider
             */
            $this->commands([
                PublishNgeniusProviders::class,
                CopyNgeniusMigrationFiles::class,
                CopyNgeniusWebhooks::class,
            ]);
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(){

        $this->mergeConfigFrom(
            __DIR__.'/../../Config/ngenius-config.php', 'ngenius-config'
        );

        
        $this->app->bind('ngenius',fn($app)=>new NgeniusFacades($app));

        $this->app->alias('ngenius', NgeniusFacades::class);

    }
}