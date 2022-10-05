<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCardUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'employee_id', 'name', 'position', 'department', 'business_unit', 'gender', 'phone',
        'mobile', 'email', 'website', 'facebook_url',
        'twitter_url', 'linkedin_url', 'image_url', 'gender','url_code','business_unit_id'
    ];

    function getBusinessUnitAttribute(){
        return BusinessUnit::find($this->business_unit_id);
    }
    function getBusinessUnitViewAttribute(){
        return BusinessCardBusinessUnitView::where('business_unit_id',$this->business_unit_id)->first();
    }

    function upload($requestFileName)
    {
        $request = request();

        $file = $request->file($requestFileName);
        $name = str_random(6) . '_profile.png';

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

        return "/e_card/profile-images/" . $this->id .'/'. $name;
    }


    function getGeneratedURLAttribute(){

    }

    function getImageAttribute(){
        if($this->image_url == '' || $this->image_url == null ){
            if($this->gender == 1){
                return asset('images/ecard/team-012.png');

            }else{
                return asset('images/ecard/team-01.png');
            }
        }
        else{

            $basename = str_replace('+', ' ', urlencode(basename($this->image_url)));
            $dirname = dirname($this->image_url);
            $path = $dirname . '/' . $basename;
            return url('/storage' . $path);
        }

    }
}
