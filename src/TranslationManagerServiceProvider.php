<?php

namespace OpenAdmin\TranslationManager;

use Illuminate\Support\ServiceProvider;
use OpenAdmin\TranslationManager\TranslationManager;

class TranslationManagerServiceProvider extends ServiceProvider
{
    /**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;    

     public function register()
     {        
 
         $this->app->singleton('translation-manager', function ($app) {
             $manager = $app->make('OpenAdmin\TranslationManager\Manager');
             return $manager;
         });
 
         $this->app->singleton('command.translation-manager.reset', function ($app) {
             return new Console\ResetCommand($app['translation-manager']);
         });
         $this->commands('command.translation-manager.reset');
 
         $this->app->singleton('command.translation-manager.import', function ($app) {
             return new Console\ImportCommand($app['translation-manager']);
         });
         $this->commands('command.translation-manager.import');
 
         $this->app->singleton('command.translation-manager.find', function ($app) {
             return new Console\FindCommand($app['translation-manager']);
         });
         $this->commands('command.translation-manager.find');
 
         $this->app->singleton('command.translation-manager.export', function ($app) {
             return new Console\ExportCommand($app['translation-manager']);
         });
         $this->commands('command.translation-manager.export');
 
         $this->app->singleton('command.translation-manager.clean', function ($app) {
             return new Console\CleanCommand($app['translation-manager']);
         });
         $this->commands('command.translation-manager.clean');
     }

    public function boot(TranslationManager $extension)
    {
        if (! TranslationManager::boot()) {
            return ;
        }

        $configPath = __DIR__ . '/../config/translation-manager.php';
        $this->mergeConfigFrom($configPath, 'translation-manager');        
        
        $migrations = $extension->migrations();
        $views = $extension->views();
        $this->loadViewsFrom($views, 'oa-translation-manager');

        if ($this->app->runningInConsole()){

            $this->publishes([
                $views => base_path('resources/views/vendor/open-admin-ext/translation-manager'),
            ], 'views');

            $this->publishes([
                $migrations => base_path('database/migrations'),
            ], 'migrations');

            $this->publishes([
                $configPath => config_path('translation-manager.php')
            ], 'config');
        }

        $this->app->booted(function () {
            TranslationManager::routes(__DIR__ . '/../routes/web.php');
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('translation-manager',
            'command.translation-manager.reset',
            'command.translation-manager.import',
            'command.translation-manager.find',
            'command.translation-manager.export',
            'command.translation-manager.clean'
        );
	}    
}
