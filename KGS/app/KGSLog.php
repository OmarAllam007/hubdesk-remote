<?php

namespace KGS;

use Illuminate\Database\Eloquent\Model;

class KGSLog extends Model
{
    protected $table = 'kgs_logs';
    protected $fillable = ['document_id','level_id','type'];

    const UPDATE_DOCUMENT_TYPE = 1;
    const NOTIFICATION_TYPE = 2;


}
