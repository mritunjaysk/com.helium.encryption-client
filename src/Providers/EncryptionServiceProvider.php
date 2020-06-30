<?php

namespace Helium\Encryption\Providers;

use Helium\Encryption\Encryption;
use Helium\Encryption\Engines\TextTalkEngine;
use Illuminate\Support\ServiceProvider;

class EncryptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/encryption.php', 'encryption');

        $this->app->singleton(Encryption::class, function ($app) {
            $encryption = new Encryption('text-talk');
            $encryption->extend('text-talk', new TextTalkEngine(
                config('encryption.host', 'ws://localhost:7050'),
                config('encryption.algorithm', 'aes-256-cbc'),
                config('encryption.password')
            ));

            $encryption->connect();

            return $encryption;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/encryption.php' => config_path('encryption.php')
        ], 'config');
    }
}