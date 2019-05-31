<?php

namespace KGS;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BusinessDocumentRole extends Model
{
    protected $table = 'bu_documents_roles';

    function getUsersAtrribute(){
        return $this->hasMany(User::class);
    }
}
