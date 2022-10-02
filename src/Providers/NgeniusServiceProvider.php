<?php

namespace Jeybin\Networkintl\Providers;

use Illuminate\Support\ServiceProvider;
use Jeybin\Networkintl\Facades\NgeniusFacades;
use Jeybin\Networkintl\Console\InstallNgeniusPackage;

class NgeniusServiceProvider extends ServiceProvider
{


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
        require_once __DIR__ . '/../Helpers/ResponseHelper.php';


        /**
         * Publishing the migration files
         */
        $timestamp              = date('Y_m_d_His', time());

        /**
         * Path of the migration file inside the composer package
         */
        $migration_package_path = __DIR__.'/../../database/migrations/create_ngenius_table.php.stub';

        /**
         * Migration file path where it need to be copied
         */
        $migration_project_path = database_path("migrations/ngenius/{$timestamp}_create_ngenius_table.php");

        /**
         * Configurations needed to be published,
         * can publish multiple files, add 
         * more into the array
         */
        $publishConfigs = [$migration_package_path =>$migration_project_path];

        /**
         * Publishes the configuration files
         * with a tag name ngenius can use any tag 
         * name, use the same name while publishing the 
         * vendor 
         */
        $this->publishes($publishConfigs, 'ngenius');


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
                InstallNgeniusPackage::class,
            ]);
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(){


        $this->app->bind('ngenius',fn($app)=>new NgeniusFacades($app));

        $this->app->alias('ngenius', NgeniusFacades::class);

    }
}