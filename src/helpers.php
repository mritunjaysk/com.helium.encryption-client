<?php

if (!function_exists('encryption'))
{
    function encryption(string $engine = null)
    {
        return app(\Helium\Encryption\Encryption::class)->engine($engine);
    }
}