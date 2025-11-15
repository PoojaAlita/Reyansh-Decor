<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPage extends Model
{
    protected $fillable = [
        'title',
        'url',
        'icon',
        'parent_id',
        'sortorder',
        'isshown'
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->updated_at = null;
        });
    }

    public function parent()
    {
        return $this->belongsTo(AdminPage::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AdminPage::class, 'parent_id')->orderBy('sortorder');
    }
}
