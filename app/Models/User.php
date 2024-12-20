<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'config'
    ];

    public function teachers() {
        return $this->hasMany(Teacher::class, "user_id", "user_id");
    }

    public function subjects() {
        return $this->hasMany(Subject::class, "user_id", "user_id");
    }

    public function schedules() {
        return $this->hasMany(Schedule::class, "user_id", "user_id");
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $user->teachers()->delete();
        });
    }

    public function config(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $path = "upload/" . $this->attributes["user_id"] . "/config.json";
                if (\Storage::exists($path)) {
                    return json_decode(\Storage::get($path), true);
                }
                return [];
            },
            set: function ($value) {
                $path = "upload/" . $this->attributes["user_id"] . "/config.json";
                \Storage::put($path, json_encode($value));
            }
        );
    }
}
