<?php namespace AuraIsHere\FireAndForget;

use Illuminate\Support\ServiceProvider;

class FireAndForgetServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('aura-is-here/fire-and-forget');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fire-and-forget', function ($app) {
            $connectionTimeout = $app['config']->get('fire-and-forget::connection_timeout');

            return new FireAndForget($connectionTimeout);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
