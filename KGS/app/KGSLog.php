<?php

namespace KGS;

use Illuminate\Database\Eloquent\Model;

class KGSLog extends Model
{
    protected $table = 'kgs_logs';
    protected $fillable = ['document_id','level_id','type','user_id','old_data','new_data'];

    protected $casts = ['old_data' => 'array', 'new_data' => 'array'];

    const UPDATE_DOCUMENT_TYPE = 1;
    const NOTIFICATION_TYPE = 2;


}
