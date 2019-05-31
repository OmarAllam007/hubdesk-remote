<?php

namespace KGS;

use Illuminate\Database\Eloquent\Model;

class DocumentNotification extends Model
{
    protected $table = "document_notification_levels";

    protected $fillable = ["business_unit_id", "level", "days", "users"];
    protected $casts = ["users" => "array"];


}
