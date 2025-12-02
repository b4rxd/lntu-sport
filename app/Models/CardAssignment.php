<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CardAssignment extends Model
{
    use HasUuids;

    protected $table = 'card_assignments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'card_id',
        'client_id',
        'subscription_id',
        'assigned_date',
        'returned_date',
        'created_by_id'
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    public function card(){
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
