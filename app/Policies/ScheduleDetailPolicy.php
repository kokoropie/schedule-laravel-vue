<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\ScheduleDetail;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScheduleDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Schedule $schedule): Response
    {
        return $user->user_id == $schedule->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule, ScheduleDetail $scheduleDetail): Response
    {
        return $user->user_id == $schedule->user_id && $schedule->schedule_id == $scheduleDetail->schedule_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Schedule $schedule): Response
    {
        return $user->user_id == $schedule->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule, ScheduleDetail $scheduleDetail): Response
    {
        return $this->view($user, $schedule, $scheduleDetail);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule, ScheduleDetail $scheduleDetail): Response
    {
        return $this->update($user, $schedule, $scheduleDetail);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule, ScheduleDetail $scheduleDetail): Response
    {
        return $this->delete($user, $schedule, $scheduleDetail);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule, ScheduleDetail $scheduleDetail): Response
    {
        return $this->delete($user, $schedule, $scheduleDetail);
    }
}
