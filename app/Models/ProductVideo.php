<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    protected $table = 'product_videos';

    protected $fillable = [
        'product_id',
        'title',
        'video_url',
        'thumbnail',
        'position',
        'ishown',
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
