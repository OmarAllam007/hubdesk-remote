<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCardUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'name', 'position', 'department', 'business_unit', 'phone', 'mobile', 'website', 'facebook_url', 'twitter_url', 'linkedin_url', 'image_url', 'gender'
    ];

    function upload($requestFileName)
    {
        $request = request();

        $file = $request->file($requestFileName);
        $name = str_random(6).'_profile.png';

        $folder = storage_path("app/public/e_card/profile-images/" . $this->id . '/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }
        $path = $folder . $name;

        if (is_file($path)) {
            $filename = uniqid() . '_' . $name;
            $path = $folder . $filename;
        }

        $file->move($folder, $name);

        return "/e_card/profile-images/" . $this->id . '/' . $name;
    }
}
