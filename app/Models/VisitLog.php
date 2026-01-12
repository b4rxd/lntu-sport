<?php

namespace App\Models;

use App\Enums\VisitLogType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    use HasUuids;

    protected $table = 'visit_log';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'subscription_id',
        'location_id',
        'type' => VisitLogType::class,
        'paid_amount',
        'created_by_id'
    ];

    public function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function location(){
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}