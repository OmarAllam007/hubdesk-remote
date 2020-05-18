<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['id', 'language'];
    static $LANGUAGES = ['en' => 'English', 'ar' => 'Arabic', 'in' => 'Indian', 'ur' => 'URDU','nep'=>'Nepali'];
    const ENGLISH = 'en';
    const ARABIC = 'ar';
    const INDIAN = 'in';
    const URDU = 'ur';
    const NEPALI = 'nep';


    function translations()
    {
        return $this->belongsToMany(Language::class);
    }
}
