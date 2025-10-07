<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'products';

    protected $fillable = [
        'id',
        'duration_days',
        'title',
        'description',
        'enabled',
        'type',
        'count_usage'
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'product_id', 'id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_products', 'product_id', 'location_id');
    }

}
