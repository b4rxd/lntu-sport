<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacationScheduler extends Model
{
    use HasUuids;

    protected $table = 'vacation_schedulers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'location_id',
        'title',
        'date_from',
        'date_till',
        'day_number',
    ];

    protected $casts = [
        'date_from' => 'datetime',
        'date_till' => 'datetime',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
