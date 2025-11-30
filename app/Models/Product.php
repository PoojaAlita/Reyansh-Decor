<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'child_category_id',
        'admin_id',
        'name',
        'sku',
        'description',
        'price',
        'sale_price',
        'stock',
        'isshown',
        'main_image'
    ];

    public $timestamps = true;

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->updated_at = null;
        });
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory() {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function childCategory() {
        return $this->belongsTo(ChildCategory::class, 'child_category_id');
    }
}
