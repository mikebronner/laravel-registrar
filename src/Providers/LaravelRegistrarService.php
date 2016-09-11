<?php namespace GeneaLabs\LaravelRegistrar\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelRegistrarService extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        include __DIR__ . '/../../routes/web.php';

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'genealabs-laravel-registrar');
        $this->publishes([
            __DIR__ . '/../../public' => public_path(),
        ], 'assets');
    }

    public function register()
    {

    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['genealabs-laravel-registrar'];
    }
}
