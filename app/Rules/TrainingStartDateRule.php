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
        if (request()->has('lang') && request()->get('lang') == 'ar') {
            return 'تاريخ بداية التدريب يجب أن يكون بعد ٤ أسابيع أو أكثر من تاريخ تقديم الطلب';

        }
        return t('The training start date should be after 4 weeks from the application date');
    }
}
