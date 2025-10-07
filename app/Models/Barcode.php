<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Barcode extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'barcodes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'barcode',
        'card_id',
        'is_generated'
    ];

    protected $casts = [
        'is_generated' => 'boolean',
    ];
}
