<?php

namespace App\Domain\Core\Models;

use App\Models\BaseModel;

class Setting extends BaseModel
{
    protected $fillable = [
        'group', 'key', 'value', 'type', 'description', 'is_public',
    ];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    /**
     * Get the typed value.
     */
    public function getTypedValueAttribute(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($this->value) ? (float) $this->value : null,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    public function scopeInGroup($query, string $group) { return $query->where('group', $group); }
    public function scopePublic($query) { return $query->where('is_public', true); }

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->typed_value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => is_array($value) ? json_encode($value) : (string) $value]);
    }
}
