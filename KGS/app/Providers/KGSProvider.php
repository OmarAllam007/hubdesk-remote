<?php

namespace KGS\Providers;

use App\Providers\EventServiceProvider;
use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class KGSProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mapSubcontractorsRoutes();
        $this->loadViewsFrom($this->basePath('resources/views'), 'kgs');
        $this->loadMigrationsFrom($this->basePath('database/migrations'));
        $this->loadConfig();
//        $this->commands([
//            \Subcontractors\Console\Commands\LinkAssetsCommand::class
//        ]);
//
//        app(Factory::class)->load($this->basePath('database/factories'));
    }

    private function basePath($path)
    {
        return dirname(dirname(__DIR__)) . '/' . $path;
    }

    private function loadConfig()
    {
        if ($this->isConfigCached()) {
            return false;
        }

        $files = new \FilesystemIterator($this->basePath('config'));
        foreach ($files as $file) {
            list($name, $_) = explode('.', $file->getBasename());
            $this->mergeConfigFrom($file->getPathname(), $name);
        }
    }

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    protected function mapSubcontractorsRoutes()
    {
        $this->app->make('router')->group([
            'middleware' => 'web',
            'namespace' => 'KGS\Http\Controllers',
            'prefix' => 'kgs',
            'as' => 'kgs.',
        ], function ($router) {
            require $this->basePath('routes/web.php');
        });
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge_recursive(require $path, $config));
    }

    protected function isConfigCached()
    {
        return $this->app['files']->exists($this->app->bootstrapPath('cache/config.php'));
    }


}
