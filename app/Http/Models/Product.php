<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;
    public $timestamps = false;

    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Product';
    protected static $submitEmptyLogs = false;

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function currency() {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
