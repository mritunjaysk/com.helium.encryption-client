<?php

namespace Helium\Encryption\Tests\Models;

use Helium\Encryption\Traits\EncryptsAttributes;
use Illuminate\Database\Eloquent\Model;

class EncryptsAttributesModel extends Model
{
    use EncryptsAttributes;

    protected $fillable = [
        'data',
        'data_encrypted'
    ];

    protected $encryptedAttributes = [
        'data_encrypted'
    ];
}