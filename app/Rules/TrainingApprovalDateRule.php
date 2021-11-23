<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class TrainingApprovalDateRule implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        return Carbon::now()->diffInWeeks(Carbon::parse($value)) >= 2;
    }

    public function message()
    {
        if (request()->has('lang') && request()->get('lang') == 'ar') {
            return 'يجب أن يكون التاريخ النهائي للجامعة لموافقة الشركة في خلال أسبوعين من تقديم الطلب';
        }
        return 'The University deadline for Company approval should be within 2 weeks from the application date.';
    }
}
