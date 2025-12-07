<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HomeVideo extends Model
{
    protected $table = 'home_videos';

    protected $fillable = [
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
