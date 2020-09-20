<?php

namespace App\Behaviors;

use App\Availability;
use App\Group;
use App\ServiceLimit;
use App\ServiceUserGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use PhpParser\Node\Expr\Cast\Int_;

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
            $groups = Group::whereType(Group::REQUESTER)->whereIn('id', $service_groups->toArray())->get();
            foreach ($groups as $group) {
                if ($group->users && in_array($user->id, $group->users->pluck('id')->toArray())) {
                    return true;
                }
            }
        }

        return false;
    }

    public function available()
    {
        $requester_bu_id = auth()->user()->business_unit_id;
        $can_display_the_service = true;

        foreach ($this->availabilities as $availability) {
            if (in_array($requester_bu_id, $availability->value)) {
                $can_display_the_service = $this->validDate($availability);
            }

        }
        return $can_display_the_service;
    }

    function validDate($availability)
    {
        if ($availability->type == Availability::DAY && now()->day < $availability->available_until) {
            return true;
        } elseif ($availability->type == Availability::MONTH && (now()->month != $availability->available_until)) {
            return true;
        } elseif ($availability->type == Availability::Year && (now()->year != $availability->available_until)) {
            return true;
        }

        return false;
    }

    public static function uploadAttachment($folderName, $service, UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();

        $folder = storage_path('app/public/attachments/' . $folderName . '/' . $service->id . '/');
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $path = $folder . $filename;
        if (is_file($path)) {
            $filename = uniqid() . '_' . $filename;
            $path = $folder . $filename;
        }

        $file->move($folder, $filename);

        return '/attachments/' . $folderName . '/' . $service->id . '/' . $filename;
    }

}
