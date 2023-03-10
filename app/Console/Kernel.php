<?php

namespace App\Console;

use App\Console\Commands\AutomateInsuranceReportCommand;
use App\Console\Commands\CheckForNotOpenedTickets;
use App\Console\Commands\CheckForNotSubmittedSurveys;
use App\Console\Commands\EscalateTickets;
use App\Console\Commands\KGSPendingApprovalsNotifications;
use App\Console\Commands\LdapImportUser;
use App\Console\Commands\MotorsReportCommand;
use App\Console\Commands\RenewDocument;
use App\Console\Commands\ScheduledReportsCommand;
use App\Console\Commands\SyncByRequest;
use App\Jobs\CleanErrorLog;
use Closure;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Make\Console\Command\Module;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    protected $commands = [
        Module::class,
        LdapImportUser::class,
        Commands\LdapImportAll::class,
        Commands\AutoCloseResolvedTickets::class,
        Commands\EscalateApprovals::class,
        Commands\CalculateOpenRequestsTime::class,
        Commands\SyncServiceDeskPlus::class,
        Commands\SyncByRequest::class,
        EscalateTickets::class,
        RenewDocument::class,
        KGSPendingApprovalsNotifications::class,
        CheckForNotSubmittedSurveys::class,
        CheckForNotOpenedTickets::class,
        ScheduledReportsCommand::class,
        MotorsReportCommand::class,
        AutomateInsuranceReportCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Run the auto close tickets command twice every hour on working days
        $schedule->command('ticket:auto-close')
            ->sundays()->mondays()->tuesdays()->wednesdays()->thursdays()
            ->everyThirtyMinutes();
        // Calculate time of tickets every minute
        $schedule->command('tickets:calculate-time')->everyMinute();

        $schedule->command('tickets:check-viewed-tickets')->daily()->between('6:30', '7:30');
        $schedule->command('surveys:check')->daily()->between('6:00', '6:15');
        $schedule->command('document:renew')->daily()->between('6:00', '6:15');
        $schedule->command('reports:schedule')->monthlyOn(1)->between('7:00', '8:00');

        $schedule->job(new CleanErrorLog())->daily();

    }
}
