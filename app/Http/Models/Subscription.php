<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function sanctions () {
        return $this->belongsToMany(Sanction::class, 'subscriptions_sanctions');
    }

    public function clients() {
        return $this->belongsToMany(Client::class, 'clients_subscriptions');
    }
}
