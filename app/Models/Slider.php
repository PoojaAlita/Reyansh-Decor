<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Slider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'sub_title',
        'image',
        'link',
        'isshown',
        'admin_id',
    ];

    public $timestamps = true;

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->updated_at = null;
        });
    }
}
