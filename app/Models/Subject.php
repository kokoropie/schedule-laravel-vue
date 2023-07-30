<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['subject_id', 'name', 'credits', 'color_foreground', 'color_background', 'teacher_id'];

    public function teacher() {
        return $this->belongsTo(Teacher::class, "teacher_id", "teacher_id");
    }

    public function schedules() {
        return $this->hasMany(Schedule::class, "subject_id", "subject_id");
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (Subject $subject) {
            $subject->schedules()->delete();
        });
    }
}
