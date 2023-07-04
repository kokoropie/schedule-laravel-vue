<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';
    public $timestamps = false;
    protected $fillable = ['name'];

    public function subjects() {
        return $this->hasMany(Subject::class, "teacher_id", "teacher_id");
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (Teacher $teacher) {
            $teacher->subjects()->delete();
        });
    }
}
