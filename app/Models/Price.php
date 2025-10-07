<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'prices';

    protected $fillable = [
        'id',
        'title',
        'product_id',
        'amount_in_uah',
        'enabled',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
