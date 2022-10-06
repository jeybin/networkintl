<?php

namespace Jeybin\Networkintl\Providers;

use Illuminate\Support\ServiceProvider;
use Jeybin\Networkintl\Facades\NgeniusFacades;
use Jeybin\Networkintl\App\Console\InstallNgeniusPackage;

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
        require_once __DIR__ . '/../App/Helpers/ResponseHelper.php';


        /**
         * Publishing the migration files
         */
        $timestamp              = date('Y_m_d_His', time());

        /**
         * Path of the migration file of ngenius_gateway inside the composer package
         */
        $ngenius_gateway_package_path = __DIR__.'/../../database/migrations/create_ngenius_table.php.stub';

        /**
         * Migration file of ngenius_gateway path where it need to be copied
         */
        $ngenius_gateway_project_path = database_path("migrations/ngenius/{$timestamp}_create_ngenius_table.php");

        /**
         * Path of the migration file of ngenius_gateway_webhooks inside the composer package
         */
        $ngenius_gateway_webhook_package_path = __DIR__.'/../../database/migrations/create_ngenius_webhooks_table.php.stub';

        /**
         * Migration file of ngenius_gateway_webhooks path where it need to be copied
         */
        $ngenius_gateway_webhook_project_path = database_path("migrations/ngenius/{$timestamp}_create_ngenius_webhooks_table.php");

        /**
         * Configurations needed to be published,
         * can publish multiple files, add 
         * more into the array
         */
        $publishConfigs = [$ngenius_gateway_package_path =>$ngenius_gateway_project_path,
                           $ngenius_gateway_webhook_package_path =>$ngenius_gateway_webhook_project_path];

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