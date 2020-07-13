<?php


namespace App\Traits;


use App\Http\Models\Sanction;
use App\Http\Models\Subscription;

trait HasSubscriptionsTrait
{
    public function giveSanctionsTo(... $sanctions) {

        $sanctions = $this->getAllSanctions($sanctions);
        if($sanctions === null) {
            return $this;
        }
        $this->sanctions()->saveMany($sanctions);
        return $this;
    }

    public function withdrawSanctionsTo( ... $sanctions ) {

        $sanctions = $this->getAllSanctions($sanctions);
        $this->sanctions()->detach($sanctions);
        return $this;

    }

    public function refreshSanctions( ... $sanctions ) {

        $this->sanctions()->detach();
        return $this->giveSanctionsTo($sanctions);
    }

    public function hasSanctionTo($sanction) {

        return $this->hasSanctionThroughSubscription($sanction) || $this->hasSanction($sanction);
    }

    public function hasSanctionThroughSubscription($sanction) {

        foreach ($sanction->subscriptions as $subscription){
            if($this->subscriptions->contains($subscription)) {
                return true;
            }
        }
        return false;
    }

    public function hasSubscription( ... $subscriptions ) {

        foreach ($subscriptions as $subscription) {
            if ($this->subscriptions->contains('slug', $subscription)) {
                return true;
            }
        }
        return false;
    }

    public function subscriptions() {

        return $this->belongsToMany(Subscription::class,'clients_subscriptions');

    }
    public function sanctions() {

        return $this->belongsToMany(Sanction::class,'clients_sanctions');

    }
    protected function hasSanction($sanction) {

        return (bool) $this->sanctions->where('slug', $sanction->slug)->count();
    }

    protected function getAllSanctions(array $sanctions) {

        return Sanction::whereIn('slug',$sanctions)->get();

    }
}
