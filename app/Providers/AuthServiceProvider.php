<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use App\Models\Subject;
use App\Models\Teacher;
use App\Policies\ScheduleDetailPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SubjectPolicy;
use App\Policies\TeacherPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Teacher::class => TeacherPolicy::class,
        Subject::class => SubjectPolicy::class,
        Schedule::class => SchedulePolicy::class,
        ScheduleDetail::class => ScheduleDetailPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
