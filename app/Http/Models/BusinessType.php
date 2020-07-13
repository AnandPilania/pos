<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessType extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Business Type';
    protected static $submitEmptyLogs = false;
}
