<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['title', 'is_done'];

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
