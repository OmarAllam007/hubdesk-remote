<?php

namespace App\Http\Requests;

use App\Rules\TrainingApprovalDateRule;
use App\Rules\TrainingStartDateRule;
use Illuminate\Foundation\Http\FormRequest;

class InternshipRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required',
            'id_number' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'type' => 'required',
            'duration' => 'required',
            'start_date' => ['required', new TrainingStartDateRule()],
            'end_date' => ['required', 'after:start_date'],
            'deadline' => ['required', new TrainingApprovalDateRule()],
            'training_required' => 'required',
            'reason' => 'required',
            'cv' => 'required',
            'letter' => 'required',
            'preferred_location' => 'required',
            'previous_training' => 'required',

        ];
    }

    public function messages()
    {
        if($this->has('lang') && $this->get('lang') == 'ar'){
            return [
                'full_name.required' => 'حقل الإسم مطلوب',
                'id_number.required' => 'حقل رقم الهوية مطلوب',
                'gender.required' => 'حقل النوع مطلوب',
                'phone.required' => 'حقل رقم الجوال مطلوب',
                'email.required' => 'حقل البريد الالكتروني مطلوب',
                'address.required' => 'حقل العنوان مطلوب ',
                'city.required' => 'حقل مدينة الإقامة مطلوب',
                'type.required' => 'حقل نوع التدريب مطلوب',
                'duration.required' => 'حقل المدة مطلوب',
                'start_date.required' => 'حقل تاريخ بداية التدريب مطلوب',
                'end_date.required' => 'حقل تاريخ نهاية التدريب مطلوب' ,
                'deadline.required' => 'حقل تاريخ الموعد النهائي للجامعة لموافقة الشركة مطلوب',
                'training_required.required' => 'حقل خطة التدريب للجامعة مطلوب',
                'reason.required' => 'يرجى ذكر السبب من طلب التدريب',
                'cv.required' => 'إرفاق السيرة الذاتية مطلوب',
                'letter.required' => 'إرفاق خطاب الجامعة مطلوب',
                'preferred_location.required' => 'حقل المدينة مطلوب ',
                'previous_training.required' => 'حقل الخبرة السابقة مطلوب',
            ];
        }

        return [

            'type.required' => 'This field is required',
            'cv.required' => 'CV is required',
            'letter.required' => 'University letter is required'
        ];
    }
}
