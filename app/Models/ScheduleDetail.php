<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDetail extends Model
{
    use HasFactory;
    protected $table = 'schedule_details';
    protected $primaryKey = 'schedule_detail_id';
    public $timestamps = false;
    protected $fillable = ['subject_id', 'start', 'end', 'from', 'to', 'type', 'dateOfWeek'];

    public function subject() {
        return $this->belongsTo(Subject::class, "subject_id", "subject_id");
    }
}
