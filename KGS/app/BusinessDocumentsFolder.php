<?php

namespace KGS;

use App\BusinessUnit;
use App\User;
use Illuminate\Database\Eloquent\Model;

class BusinessDocumentsFolder extends Model
{
    protected $table = 'documents_folder';

    protected $fillable = ['business_unit_id', 'name'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }



}
