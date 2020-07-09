<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    //
    protected $table = 't_categories';

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Products::class, 'category_id', 'id')->with('currency');
    }

}
