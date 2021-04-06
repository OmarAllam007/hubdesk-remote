<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Status;
use App\Ticket;

class TicketReplyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        $user_id = $this->user()->id;
//
        $ticket = $this->route('ticket');
        return can('reply', $ticket);
//        return in_array($user_id, [$ticket->technician_id, $ticket->requester_id, $ticket->creator_id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->addCustomRules();

//        dd($this->request->all());
        return [
            'reply.content' => 'required',
//            'reply.status' => 'check_status',
        ];
    }

    public function messages()
    {
        return ['check_status' => 'Invalid status', 'reply.content.required_without' => 'The description field is required'];
    }

    protected function addCustomRules()
    {

        \Validator::extend('check_status', function () {
            if ($this->reply['status'] == 6) {
                return false;
            }

            if (!Status::where('id',$this->reply['status'])->exists()) {
                return false;
            }

            if (!$this->user()->isTechnician() && in_array($this->reply['status'], [2, 3, 5])) {

            }

            /** @var Ticket $ticket */
            $ticket = $this->route()->parameter('ticket');
            if ($this->user()->id == $ticket->technician_id && $this->reply['status'] == 8) {
                return false;
            }

            return true;
        });
    }

    public function response(array $errors)
    {

        flash(t('Reply Info'), t('Cannot send reply') ,'error');
        return \Redirect::back()->withErrors($errors)->withInput($this->all());
    }

    public function forbiddenResponse()
    {

        flash(t('Reply Info'), t('You cannot add reply to this ticket'),'error');
        return \Redirect::back();
    }
}
