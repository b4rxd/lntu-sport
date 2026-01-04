<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Location extends Model
{
    use HasUuids;

    protected $table = 'locations';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'description',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function regularSchedulers()
    {
        return $this->hasMany(RegularScheduler::class, 'location_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'location_product', 'location_id', 'product_id');
    }
}