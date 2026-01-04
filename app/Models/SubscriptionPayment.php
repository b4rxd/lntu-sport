<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasUuids;

    protected $table = 'subscription_payment';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'subscription_id',
        'price_id',
        'paid_amount'
    ];

    protected $casts = [
        'end_date' => 'datetime'
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function price(){
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }
}
