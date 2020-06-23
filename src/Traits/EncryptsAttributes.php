<?php

namespace Helium\Encryption\Traits;

use Helium\LaravelHelpers\Traits\HasFailedEvents;
use Illuminate\Database\Eloquent\Model;

trait EncryptsAttributes
{
    use HasFailedEvents;

    public static function bootEncryptsAttributes()
    {
        static::retrieved(function(Model $model) {
            $model->decryptAttributes();
        });

        static::saving(function(Model $model) {
            $model->encryptAttributes();
        });

        static::saved(function(Model $model) {
            $model->decryptAttributes();
        });

        static::saveFailed(function(Model $model) {
            $model->decryptAttributes();
        });
    }

    public function getEncryptedAttributes(): array
    {
        if (method_exists($this, 'encryptedAttributes'))
        {
            return $this->encryptedAttributes();
        }

        return $this->encryptedAttributes ?? [];
    }

    public function encryptAttributes()
    {
        foreach ($this->getEncryptedAttributes() as $attribute)
        {
            if (!is_null($this->$attribute))
            {
                $this->attributes[$attribute] = encryption()->encrypt($this->$attribute);
            }
        }
    }

    public function decryptAttributes()
    {
        foreach ($this->getEncryptedAttributes() as $attribute)
        {
            if (!is_null($this->$attribute))
            {
                $this->attributes[$attribute] = encryption()->decrypt($this->$attribute);
            }
        }
    }
}