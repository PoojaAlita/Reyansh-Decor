<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class SubCategory extends Model
{
     protected $table = 'subcategories';

    protected $fillable = [
        'category_id',
        'subcat_name',
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
    // Relation with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
