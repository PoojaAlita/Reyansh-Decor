<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $table = "categories";

    protected $fillable = [
        'name',
        'isshown',
        'admin_id'
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->updated_at = null;
        });
    }
}
