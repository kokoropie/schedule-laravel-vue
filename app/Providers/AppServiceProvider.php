<?php

namespace App\Providers;

use App\Models\Schedule;
use App\Models\ScheduleDetail;
use App\Models\Subject;
use App\Models\Teacher;
use App\Policies\ScheduleDetailPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SubjectPolicy;
use App\Policies\TeacherPolicy;
use Gate;
use Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::policy(Teacher::class, TeacherPolicy::class);
        Gate::policy(Subject::class, SubjectPolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(ScheduleDetail::class, ScheduleDetailPolicy::class);

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('laravelpassport', \SocialiteProviders\LaravelPassport\Provider::class);
        });
    }
}
