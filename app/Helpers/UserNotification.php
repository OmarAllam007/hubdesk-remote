<?php

namespace App\Helpers;

use App\User;
use Illuminate\Notifications\DatabaseNotification;

class UserNotification
{
    /**
     * @var DatabaseNotification
     */
    private $notification;
    public $string;
    public $url;

    const NEW_APPROVAL_ASSIGNED = 'App\Notifications\NewApprovalAssignedNotification';
    const APPROVAL_UPDATED = 'App\Notifications\ApprovalUpdatedNotification';
    const NEW_TICKET_ASSIGNED = 'App\Notifications\TicketAssigned';
    const NEW_REPLY = 'App\Notifications\NewReplyCreatedNotification';

    public function __construct(DatabaseNotification $notification)
    {
        $this->notification = $notification;


        $this->getUrlDescription();
        $this->getStringDescription();

    }

    private function getStringDescription()
    {

        if ($this->notification->type == self::NEW_TICKET_ASSIGNED) {
            $this->getNewTicketString();
        } else if ($this->notification->type == self::NEW_APPROVAL_ASSIGNED) {
            $this->getNewApprovalAssignedString();
        }else if ($this->notification->type == self::NEW_REPLY){
            $this->getNewReplyString();
        }
        else if ($this->notification->type == self::APPROVAL_UPDATED){
            $this->getApprovalUpdatedString();
        }
    }

    private function getUrlDescription()
    {
        $this->url = '/ticket/' . $this->notification->data['ticket_id'];
    }

    private function getNewTicketString()
    {
        $this->string = 'New Ticket has been assigned to you ticket #' . $this->notification->data['ticket_id'];
    }

    private function getNewApprovalAssignedString()
    {
        $this->string = 'New Approval has been assigned to you ticket #' . $this->notification->data['ticket_id'];
        $this->url = '/approval/' . $this->notification->data['approval_id'];
    }

    private function getNewReplyString()
    {
        $user = User::find($this->notification->data['user_id']);
        $this->string = "{$user->name} reply on the ticket #" . $this->notification->data['ticket_id'];
    }

    private function getApprovalUpdatedString()
    {

        $user = User::find($this->notification->data['approver_id']);

        $status = $this->notification->data['status'];
        $statusDescription = $status ? 'approve' : 'deny';
        $this->string = "{$user->name} has {$statusDescription} the ticket #" . $this->notification->data['ticket_id'];
        $this->url = '/ticket/' . $this->notification->data['ticket_id'];
    }
}