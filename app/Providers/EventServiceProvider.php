<?php

namespace App\Providers;

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
            'App\Listeners\TaskCreatedListener',
            'App\Listeners\TicketNotAssignedListener',
            'App\Listeners\CreateUserTicketListener',
        ],
        'eloquent.created: App\LetterTicket' => [
            'App\Listeners\Letters\LetterTicketListener',
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