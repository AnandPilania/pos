<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    public function subscriptions() {
        return $this->belongsToMany(Subscription::class, 'subscriptions_sanctions');
    }

    public function clients() {
        return $this->belongsToMany(Client::class, 'clients_sanctions');
    }
}
