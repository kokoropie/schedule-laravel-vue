<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;
    protected $fillable = ['name', 'numOfClassPeriodsPerDay', 'timeOfEachClassPeriod'];

    protected $casts = [
        'timeOfEachClassPeriod' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id", "user_id");
    }

    public function share() {
        return $this->hasOne(ScheduleShare::class, "schedule_id", "schedule_id");
    }

    public function details() {
        return $this->hasMany(ScheduleDetail::class, "schedule_id", "schedule_id");
    }

    protected static function booted(): void
    {
        static::deleting(function (Schedule $schedule) {
            $schedule->share()->delete();
            $schedule->details()->delete();
        });
    }
}
