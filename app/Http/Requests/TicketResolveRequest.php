<?php

namespace App\Http\Requests;

class TicketResolveRequest extends Request
{
    public function authorize()
    {
        return can('resolve', $this->route('ticket'));
    }

    public function rules()
    {
        return [
            'content' => 'required_without:template',
        ];
    }

    public function messages()
    {
        return ['content.required_without'=>'The description field is required'];
    }
}
