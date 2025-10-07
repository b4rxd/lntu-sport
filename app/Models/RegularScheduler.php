<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegularScheduler extends Model
{
    use HasUuids;

    protected $table = 'regular_schedulers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'location_id',
        'date_from',
        'date_till',
        'day_number',
        'time_from',
        'time_till',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'date_from' => 'datetime',
        'date_till' => 'datetime',
        'time_from' => 'datetime:H:i:s',
        'time_till' => 'datetime:H:i:s',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
