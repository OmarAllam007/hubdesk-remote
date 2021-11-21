<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class TrainingStartDateRule implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        return Carbon::now()->diffInWeeks(Carbon::parse($value)) >= 4;
    }

    public function message()
    {
        return t('The training start date should be after 4 weeks from the application date');
    }
}
