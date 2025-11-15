<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRight extends Model
{
    protected $fillable = ['admin_id', 'page_id'];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    public function page()
    {
        return $this->belongsTo(AdminPage::class);
    }
}
