<?php

namespace App\Models;

use App\Enums\CardStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Card extends Model
{
    use HasUuids;

    protected $table = 'cards';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'barcode',
        'status',
        'created_by_id'
    ];

    protected $casts = [
        'status' => CardStatus::class,
        'issued_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function subscription(){
        return $this->hasOne(Subscription::class, 'card_id', 'id');
    }

    public function cardAssignments(){
        return $this->hasMany(CardAssignment::class, 'card_id', 'id');
    }
}