<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasUuids;

    protected $table = 'subscriptions';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'card_id',
        'price_id',
        'client_id',
        'created_by_id',
        'end_date',
        'count_usage',
        'status' => SubscriptionStatus::class
    ];

    protected $casts = [
        'end_date' => 'datetime'
    ];

    public function card(){
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

    public function price(){
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function cardAssignments(){
        return $this->hasMany(CardAssignment::class, 'subscription_id', 'id');
    }

     public function subscriptionsPayments(){
        return $this->hasMany(SubscriptionPayment::class, 'subscription_id', 'id');
    }
}
