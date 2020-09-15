<?php

namespace App\Http\Requests;

class ApprovalRequest extends Request
{
    public function authorize()
    {
        $ticket = $this->route()->parameter('ticket');
        return can('submit_approval', $ticket);
//        return true;
    }

    public function rules()
    {
        return [
            'approver_id' => 'required',
            'content' => 'required_without:template',
        ];
    }

    public function messages()
    {
        flash(t('Approval Info'), t('Cannot send approval'), 'error');
        return ['content.required_without' => 'The description field is required'];
    }

    public function response(array $errors)
    {
        flash(t('Approval Info'), t('Cannot send approval'), 'error');
        return \Redirect::back()->withErrors($errors)->withInput($this->all());
    }

    public function forbiddenResponse()
    {
        flash(t('Approval Info'), t('You cannot add approval for this ticket'), 'error');
        return \Redirect::back();
    }
}
