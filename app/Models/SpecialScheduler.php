<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialScheduler extends Model
{
    use HasUuids;

    protected $table = 'special_schedulers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'location_id',
        'date_from',
        'date_till',
        'time_from',
        'time_till',
    ];

    protected $casts = [
        'date_from' => 'datetime',
        'date_till' => 'datetime',
        'time_from' => 'datetime',
        'time_till' => 'datetime',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
