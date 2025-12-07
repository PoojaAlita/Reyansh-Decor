<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HomeBanner extends Model
{
    protected $table = 'home_banners';

    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'isshown',
        'admin_id',
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

