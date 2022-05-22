<?php

namespace App\Providers;

use App\Observers\TicketReplyObserver;
use App\TicketReply;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setWeekendDays([Carbon::SATURDAY, Carbon::FRIDAY]);
        TicketReply::observe(TicketReplyObserver::class);
        Paginator::useBootstrap();

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
