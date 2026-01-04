<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'type' => ProductType::class,
        'count_usage',
        'infinite',
        'enabled'
    ];

    protected $casts = [
        'type' => ProductType::class,
        'infinite' => 'boolean',
        'enabled' => 'boolean'
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
