<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id')->with('currency');
    }

}
