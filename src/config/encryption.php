<?php

return [
    'host' => env('ENCRYPTION_HOST'),
    'algorithm' => env('ENCRYPTION_ALGORITHM', 'aes-256-cbc'),
    'password' => env('ENCRYPTION_PASSWORD')
];