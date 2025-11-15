<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminIcon extends Model
{
    // The table associated with the model.
    protected $table = 'admin_icons';

    // The attributes that are mass assignable.
    protected $fillable = ['title', 'class', 'isshown'];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->updated_at = null;
        });
    }
}
