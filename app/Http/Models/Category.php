<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Category';
    protected static $submitEmptyLogs = false;

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id')->with('currency');
    }

}
