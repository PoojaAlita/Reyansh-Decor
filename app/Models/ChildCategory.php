<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChildCategory extends Model
{
     protected $table = 'child_categories';

    protected $fillable = [
        'subcategory_id',
        'name',
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

    public function subcategory() {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
}
