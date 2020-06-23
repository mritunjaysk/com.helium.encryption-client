<?php

namespace Helium\Encryption\Providers;

use Helium\Encryption\Encryption;
use Helium\Encryption\Engines\TextTalkEngine;
use Illuminate\Support\ServiceProvider;

class EncryptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Encryption::class, function ($app) {
            $encryption = new Encryption('text-talk');
            $encryption->extend('text-talk', new TextTalkEngine(
                config('encryption.host'),
                config('encryption.algorithm'),
                config('encryption.password')
            ));

            return $encryption;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/encryption.php' => config_path('helpers.php')
        ], 'config');
    }
}