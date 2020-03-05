<?php

namespace Tests\Feature;

use App\Availability;
use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    /** @test */
    public function a_user_can_display_available_service()
    {
        $service = Category::find(65);
        $user = \Auth::loginUsingId(39);

        $can_display_the_service = true;

        foreach ($service->availabilities as $availability) {
            if (in_array($user->business_unit_id, $availability->value)) {
                $can_display_the_service = $service->validDate($availability);
            }
        }

        $this->assertTrue($can_display_the_service);
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

}
