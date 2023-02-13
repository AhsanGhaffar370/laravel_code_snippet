<?php

namespace App\Traits;

use App\CompanySetting;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for getting key values.
| $key = Key name
*/

trait CompanySettingsTrait
{
    public static function getByKey($key)
    {
        // Get value of specific key
        return CompanySetting::where('key', $key)->first()->value;
    }

    public static function setByKey($key, $value)
    {
        // Increment that key value by 1
        $companySetting = CompanySetting::where('key', $key)->first();
        $companySetting->value = $value;
        return $companySetting->save();
    }
}
