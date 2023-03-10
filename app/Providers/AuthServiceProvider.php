<?php

namespace App\Providers;

use App\Attachment;
use App\Auth\KdeskUserProvider;
use App\BusinessCardUserAdminPermissions;
use App\BusinessUnit;
use App\DashboardUser;
use App\Policies\AttachmentPolicy;
use App\Policies\BusinessUnitDocumentRoles;
use App\Policies\ReportPolicy;
use App\Policies\TicketApprovalPolicy;
use App\Policies\TicketPolicy;
use App\Report;
use App\TicketApproval;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use KGS\BusinessDocumentRole;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Ticket' => TicketPolicy::class,
        TicketApproval::class => TicketApprovalPolicy::class,
        BusinessUnit::class => BusinessUnitDocumentRoles::class,
        Report::class => ReportPolicy::class,
        Attachment::class => AttachmentPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
//        \Auth::provider('hubdesk', function ($app) {
//            return new KdeskUserProvider(app(Hasher::class), config('auth.providers.users.model'));
//        });

        \Gate::before(function (User $user) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        \Gate::define('reports', function ($user) {
            // TODO: make this check if the user have privilege and remove hard coded values.
            return $user->isReporting();
        });

        \Gate::define('show_business_document', function ($user) {
            return in_array($user->id, BusinessDocumentRole::all()->pluck('user_id')->toArray());
        });

        \Gate::define('dashboard', function ($user) {
            return in_array($user->id, DashboardUser::all()->pluck('user_id')->toArray());
        });

        \Gate::define('e_card_admin', function ($user) {
            return BusinessCardUserAdminPermissions::where('user_id',$user->id)
                ->where('show',1)
                ->first() != null;
        });

        $this->registerPolicies();
    }
}
