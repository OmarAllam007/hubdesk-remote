<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    const FOR_LETTER_VIEW = 1;

    function language()
    {
        return $this->belongsTo(Translation::class);
    }
}
