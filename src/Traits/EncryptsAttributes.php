<?php

namespace Helium\Encryption\Traits;

use Helium\LaravelHelpers\Traits\HasFailedEvents;
use Illuminate\Database\Eloquent\Model;

trait EncryptsAttributes
{
    use HasFailedEvents;

    public $preSaveAttributes;

    public static function bootEncryptsAttributes()
    {
        static::registerModelEvent('booted', function (Model $model) {
            static::retrieved(function(Model $model) {
                $model->decryptAttributes();
            });

            static::saving(function(Model $model) {
                $model->stashAttributes();
                $model->encryptAttributes();
            });

            static::saved(function(Model $model) {
                $model->unstashAttributes();
            });

            static::saveFailed(function(Model $model) {
                $model->unstashAttributes();
            });
        });
    }

    public function stashAttributes()
    {
        $this->preSaveAttributes = $this->attributes;
    }

    public function unstashAttributes()
    {
        if (!empty($this->preSaveAttributes))
        {
            $this->attributes = array_merge($this->attributes, $this->preSaveAttributes);
            $this->syncOriginal();
        }
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