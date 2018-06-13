<?php
/**
 * File UuidPrimaryKey.php
 *
 * PHP Version 7
 *
 * @author Alex Sofronie <alsofronie@gmail.com>
 */

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

trait UuidPrimaryKey
{
    /*
     * This function is used internally by Eloquent models to test if the model has auto increment value
     * @returns bool Always false
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * This function overwrites the default boot static method of Eloquent models. It will hook
     * the creation event with a simple closure to insert the UUID
     *
     */
    public static function bootUuidPrimaryKey()
    {
        static::creating(function (Model $model) {
            // Only generate UUID if it wasn't set by already.
            $model->incrementing = false;
            $model->keyType = 'uuid';
            $key = $model->getKeyName();
            if (!isset($model->attributes[$key])) {
                $model->attributes[$key] = (string)Uuid::uuid4();
            }
        }, 0);

        static::retrieved(function (Model $model) {
            $model->incrementing = false;
            $model->keyType = 'uuid';
        });
    }
}
