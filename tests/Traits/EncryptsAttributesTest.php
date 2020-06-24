<?php

namespace Helium\Encryption\Tests\Traits;

use Helium\Encryption\Tests\Models\EncryptsAttributesModel;
use Helium\Encryption\Tests\TestCase;
use Illuminate\Database\QueryException;

class EncryptsAttributesTest extends TestCase
{
    public function testEncryptAttributes()
    {
        $attributes = [
            'data' => 'abc123',
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::make($attributes);

        $model->encryptAttributes();

        $this->assertEquals($attributes['data'], $model->data);
        $this->assertNotEquals($attributes['data_encrypted'], $model->data_encrypted);
        $this->assertEquals(encryption()->encrypt($attributes['data_encrypted']), $model->data_encrypted);
    }

    public function testDecryptAttributes()
    {
        $attributes = [
            'data' => 'abc123',
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::make($attributes);

        $model->encryptAttributes();
        $model->decryptAttributes();

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $model->$key);
        }
    }

    public function testCreate()
    {
        $attributes = [
            'data' => 'abc123',
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::create($attributes);

        foreach ($attributes as $key => $value)
        {
            $this->assertEquals($value, $model->$key);
        }
    }

    public function testCreateFailed()
    {
        $attributes = [
            'data' => null,
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::make($attributes);

        try {
            $model->save();

            $this->assertTrue(false);
        } catch (QueryException $t) {
            foreach ($attributes as $key => $value) {
                $this->assertEquals($value, $model->$key);
            }
        }
    }

    public function testUpdate()
    {
        $attributes = [
            'data' => 'abc123',
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::create($attributes);

        $updateAttributes = [
            'data' => 'zyx987',
            'data_encrypted' => 'zyx987'
        ];

        $model->update($updateAttributes);

        foreach ($updateAttributes as $key => $value)
        {
            $this->assertEquals($value, $model->$key);
        }
    }

    public function testUpdateFailed()
    {
        $attributes = [
            'data' => 'abc123',
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::create($attributes);

        $updateAttributes = [
            'data' => null,
            'data_encrypted' => null
        ];

        try {
            $model->flag = true;
            $model->update($updateAttributes);

            $this->assertTrue(false);
        } catch (QueryException $t) {
            foreach ($updateAttributes as $key => $value) {
                $this->assertEquals($value, $model->$key);
            }
        }
    }

    public function testRetrieve()
    {
        $attributes = [
            'data' => 'abc123',
            'data_encrypted' => 'abc123'
        ];

        $model = EncryptsAttributesModel::create($attributes);

        $model = EncryptsAttributesModel::find($model->id);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $model->$key);
        }
    }
}