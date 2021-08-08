<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterHeader extends Model
{
    protected $fillable = ['business_unit_id', 'path', 'stamp_path'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    static function upload($requestFileName, $folderPath)
    {
        $request = request();

        $file = $request->file($requestFileName);
        $name = $file->getClientOriginalName();

        $folder = storage_path("app/public/$folderPath/" . $request->business_unit_id . '/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }
        $path = $folder . $name;

        if (is_file($path)) {
            $filename = uniqid() . '_' . $name;
            $path = $folder . $filename;
        }

        $file->move($folder, $name);

        return "/$folderPath/" . $request->business_unit_id . '/' . $name;
    }

}
