<?php

namespace App\Policies;

use App\Report;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    function show(User $user, Report $report)
    {
        return can('reports') && in_array($user->id, $report->users()->pluck('user_id')->toArray());
    }

    function edit(User $user, Report $report)
    {

        return can('reports') && in_array($user->id, [$report->user_id]) ;
    }

    function delete(User $user, Report $report)
    {
        return can('reports') && in_array($user->id, [$report->user_id]);
    }
}
