<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformInformation extends Model
{
    protected $table = 'platform_information';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * Get the value of the platform information entry with the given name.
     */
    public static function getValue(string $name, ?string $default = null): ?string
    {
        return static::find($name)?->value ?? $default;
    }
}
