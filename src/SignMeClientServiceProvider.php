<?php

namespace Maxtereshin\SignMeClient;

use Illuminate\Support\ServiceProvider;

class SignMeClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/signmeclient.php' => config_path('signmeclient.php'),
        ]);

    }
}
