<?php

namespace App\Providers;

use App\Listeners\DaamTicketCreatedListener;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $listen = [
        'eloquent.creating: App\Ticket' => [
            'App\Listeners\InitCreatingTicketListener',
        ],
        'eloquent.created: App\Ticket' => [
            'App\Listeners\TicketCreatedListener',
            'App\Listeners\TicketFieldsListener',
            'App\Listeners\TaskCreatedListener',
            'App\Listeners\CreateUserTicketListener',
            'App\Listeners\ResetSAPTicketListener',
            'App\Listeners\DaamTicketCreatedListener'
        ],
        'eloquent.created: App\LetterTicket' => [
            'App\Listeners\Letters\LetterTicketListener',
        ],

        'eloquent.updated: App\TicketApproval' => [
            'App\Listeners\TicketApprovalListener',
            'App\Listeners\Letters\LetterTicketApprovalListener',
        ],
        'eloquent.updated: App\Ticket' => [
            'App\Listeners\Letters\LetterTaskListener',
        ]
    ];

    public function shouldDiscoverEvents()
    {

        return true;
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}