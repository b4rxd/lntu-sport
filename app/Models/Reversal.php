<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Reversal extends Model
{
    use HasUuids;

    protected $table = 'reversals';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'amount_in_uah',
        'card_id',
        'created_by_id'
    ];

    public function card(){
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
