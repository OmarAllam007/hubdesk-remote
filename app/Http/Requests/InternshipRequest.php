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
//        dd($this->all());
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
//            'location' => 'required',
            'reason' => 'required',
            'cv' => 'required',
            'letter' => 'required',
            'preferred_location' => 'required',
            'previous_training' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'This field is required',
            'cv.required' => 'CV is required',
            'letter.required' => 'University letter is required'
        ];
    }
}
