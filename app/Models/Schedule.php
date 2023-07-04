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
    protected $fillable = ['subject_id', 'start', 'end', 'from', 'to', 'type', 'dateOfWeek'];

    public function subject() {
        return $this->belongsTo(Subject::class, "subject_id", "subject_id");
    }
}
