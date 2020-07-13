<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Subscription extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Subscription';
    protected static $submitEmptyLogs = false;

    public function sanctions () {
        return $this->belongsToMany(Sanction::class, 'subscriptions_sanctions');
    }

    public function clients() {
        return $this->belongsToMany(Client::class, 'clients_subscriptions');
    }
}
