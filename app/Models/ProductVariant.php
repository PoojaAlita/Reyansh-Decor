<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'material',
        'price',
        'stock',
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
