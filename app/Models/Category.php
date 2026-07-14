<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $guarded = ['id'];

    public function complaint()
    {
        return $this->hasMany(Category::class, 'kategori_id');
    }
}
