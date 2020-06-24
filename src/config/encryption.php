<?php

return [
    'host' => env('ENCRYPTION_HOST', 'ws://localhost:7050'),
    'algorithm' => env('ENCRYPTION_ALGORITHM', 'aes-256-cbc'),
    'password' => env('ENCRYPTION_PASSWORD')
];