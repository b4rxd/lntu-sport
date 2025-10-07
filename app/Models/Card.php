<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Card extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cards';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'enabled',
        'count_usage',
        'valid_from',
        'valid_till',
        'last_usage',
        'created_by_id',
        'price_id'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'valid_from' => 'datetime',
        'valid_till' => 'datetime',
        'last_usage' => 'datetime',
    ];

     public function price()
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    public function barcode()
    {
        return $this->hasOne(Barcode::class, 'card_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function reversal(){
        return $this->hasMany(Reversal::class, 'card_id', 'id');
    }
}
    