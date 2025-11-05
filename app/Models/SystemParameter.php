<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemParameter extends Model
{
    protected $fillable = ['key','value','type','description','updated_by'];
    public $timestamps = true;
    public static function get($key, $default = null)
    {
        $p = static::where('key', $key)->first();
        return $p ? $p->value : $default;
    }
}
