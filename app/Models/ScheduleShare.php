<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleShare extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'schedule_shares';
    protected $primaryKey = 'schedule_share_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function schedule() {
        return $this->belongsTo(Schedule::class, "schedule_id", "schedule_id");
    }
}
