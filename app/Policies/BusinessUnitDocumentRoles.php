<?php

namespace App\Policies;

use App\BusinessUnit;
use App\Ticket;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessUnitDocumentRoles
{
    use HandlesAuthorization;


    public function __construct()
    {

    }

    public function show_business_unit(User $user, BusinessUnit $business_unit)
    {
        return in_array($user->id, $business_unit->document_roles->pluck('id')->toArray());
    }



}
