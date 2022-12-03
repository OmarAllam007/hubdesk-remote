<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use KGS\Document;

class DocumentRequirements extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['document_id', 'name', 'type'];

    const RENEWAL_TYPE = 1;
    const ISSUING_TYPE = 2;
    const CANCELLING_TYPE = 3;

    function document()
    {
        return $this->belongsTo(Document::class);
    }
}
