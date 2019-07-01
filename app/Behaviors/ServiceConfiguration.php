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

        $service_groups = ServiceUserGroup::where('level_id', $this->id)->where('level', $type)
            ->pluck('group_id');


        if (!$service_groups->count()) {
            return true;
        }

        if ($service_groups->count()) {
            $groups = Group::whereType(Group::REQUESTER)->whereIn('id',$service_groups->toArray())->get();
            foreach ($groups as $group) {
                if ($group->users && in_array($user->id, $group->users->pluck('id')->toArray())) {
                    return true;
                }
            }
        }

        return false;
    }

}
