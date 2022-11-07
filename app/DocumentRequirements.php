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

    function document()
    {
        return $this->belongsTo(Document::class);
    }
}
