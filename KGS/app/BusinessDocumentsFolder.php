<?php

namespace KGS;

use App\BusinessUnit;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessDocumentsFolder extends Model
{
    use SoftDeletes;

    protected $table = 'documents_folder';

    protected $fillable = ['business_unit_id', 'name','creator_id'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }


    function documents(){
        return $this->hasMany(Document::class,'folder_id');
    }

}
