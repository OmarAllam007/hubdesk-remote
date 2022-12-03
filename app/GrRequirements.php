<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrRequirements extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'service_type', 'document_type'];

    const DOCUMENT_TYPE_CR_AND_COC = 1;
    const DOCUMENT_TYPE_BALADYA = 2;
    const DOCUMENT_TYPE_CDC = 3;
    const DOCUMENT_TYPE_MODON = 4;
    const DOCUMENT_TYPE_INDUSTRY = 5;

    const SERVICE_TYPE_RENEW = 1;
    const SERVICE_TYPE_ISSUE = 2;
    const SERVICE_TYPE_CANCELLATION = 3;



}
