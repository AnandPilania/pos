<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Role';
    protected static $submitEmptyLogs = false;

    public function permissions () {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'users_roles');
    }

}
