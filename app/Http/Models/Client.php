<?php

namespace App\Http\Models;

use App\Traits\HasSubscriptionsTrait;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasSubscriptionsTrait;
    use LogsActivity;
    use CausesActivity;

    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['template_no', 'banner_color', 'category_background_color',
        'product_background_color', 'font_color'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Client';
    protected static $submitEmptyLogs = false;

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
