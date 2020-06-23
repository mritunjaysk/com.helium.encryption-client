<?php

use Faker\Generator as Faker;
use Helium\Encryption\Tests\Models\EncryptsAttributesModel;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EncryptsAttributesModel::class, function (Faker $faker) {
    return [
        'data' => 'abc123',
        'data_encrypted' => 'abc123'
    ];
});
