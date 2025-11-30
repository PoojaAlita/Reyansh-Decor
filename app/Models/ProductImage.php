<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image',
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

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
