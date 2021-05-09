<?php

namespace App\Models\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DiffForHumans implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function set($model, string $key, $value, array $attributes)
    {

        return $value;
    }
}
