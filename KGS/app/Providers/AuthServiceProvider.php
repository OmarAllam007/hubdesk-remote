<?php

namespace App\Providers;

use App\BusinessUnit;
use App\Policies\BusinessUnitDocumentRoles;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    public function boot()
    {
        $this->registerPolicies();


    }
}
