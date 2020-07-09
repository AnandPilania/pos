<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
    protected $table = 't_products';

    public $timestamps = false;

    public function category()
    {
        return $this->hasOne(Categories::class, 'id', 'category_id');
    }

    public function currency() {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
