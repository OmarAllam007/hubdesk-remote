<?php

namespace App\Behaviors;

use App\Group;
use App\ServiceUserGroup;
use Illuminate\Database\Eloquent\Builder;

/**
 * Created by PhpStorm.
 * User: hazem
 * Date: 4/17/16
 * Time: 11:25 AM
 */
trait ServiceConfiguration
{
    public function canDisplay($type)
    {
        $user = \Auth::user();

        if ($user->isAdmin()) {
            return true;
        }

        $service_groups = Group::whereType(Group::REQUESTER)->whereIn('id',ServiceUserGroup::where('level_id', $this->id)->where('level', $type)
            ->pluck('level_id')->toArray())->get();

        if (!$service_groups) {
            return true;
        }

        if ($service_groups->count()) {
            foreach ($service_groups as $group) {
                if ($group->users && in_array($user->id, $group->users->pluck('id')->toArray())) {
                    return true;
                }
            }
        }

        return false;
    }

}
