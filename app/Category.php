<?php

namespace App;

use App\Behaviors\Listable;
use App\Behaviors\ServiceConfiguration;
use App\Behaviors\SharedRelations;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use KGS\Requirement;


/**
 * App\Category
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Subcategory[] $subcategories
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category selection($empty = false)
 * @mixin \Eloquent
 */
class Category extends KModel
{
    use Listable, ServiceConfiguration, SharedRelations;

    protected $fillable = ['business_unit_id', 'name', 'description', 'service_request', 'service_cost', 'notes', 'service_type', 'is_disabled', 'logo', 'business_service_type'];

    const BUSINESS_SERVICE_TYPE = [1 => 'Individual', 2 => 'Corporate'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id', 'id');
    }

    function custom_fields()
    {
        return $this->morphMany(CustomField::class, 'level', 'level')->orderBy('order');
    }

    function levels()
    {
        return $this->hasMany(ApprovalLevels::class, 'level_id')->where('type', 1);
    }

    function fees()
    {
        return $this->hasMany(AdditionalFee::class, 'level_id')->where('level', AdditionalFee::CATEGORY);
    }

    function complaint()
    {
        return $this->hasOne(Complaint::class, 'level_id')->where('level', 'App\Category');
    }

    public function scopeQuickSearch(Builder $query)
    {
        if (\Request::has('search')) {
            $query->where(function (Builder $q) {
                $term = '%' . \Request::get('search') . '%';
                $q->where('name', 'LIKE', $term);
            });
        }

        return $query;
    }


    public function service_user_groups()
    {
        return $this->hasMany(ServiceUserGroup::class, 'level_id')->where('level', ServiceUserGroup::$CATEGORY);
    }

    function businessunits()
    {
        return $this->belongsToMany(BusinessUnit::class, 'category_business_units');
    }


    function availabilities()
    {
        return $this->hasMany(Availability::class, 'level_id');
    }


    public function scopeCanonicalList(Builder $query)
    {
        $categories = $query->with('business-unit')
            ->orderBy('name')->get()
            ->map(function ($category) {
                $category->name = $category->businessunit->name . ' > ' . $category->name;
                return $category;
            });

        return $categories->sortBy('name');
    }

    public function canonicalName()
    {
        return $this->businessunit->name . ' > ' . $this->name;
    }


    function survey()
    {
        return $this->belongsToMany(Survey::class, 'category_survey', 'category_id', 'survey_id');
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class, 'reference_id')->where('reference_type', Requirement::$types['Category']);
    }
    //
    //    public static function uploadAttachment(Category $category, UploadedFile $file)
    //    {
    //        $filename = $file->getClientOriginalName();
    //
    //        $folder = storage_path('app/public/attachments/categories/' . $category->id . '/');
    //        if (!is_dir($folder)) {
    //            mkdir($folder, 0775, true);
    //        }
    //
    //        $path = $folder . $filename;
    //        if (is_file($path)) {
    //            $filename = uniqid() . '_' . $filename;
    //            $path = $folder . $filename;
    //        }
    //
    //        $file->move($folder, $filename);
    //
    //        $final_path = '/attachments/categories/' . $category->id . '/' . $filename;
    //
    //        return $final_path;
    //    }

    function getBusinessServiceTypeStrAttribute()
    {
        if (!$this->business_service_type) {
            return 'Not Assigned';
        }
        return self::BUSINESS_SERVICE_TYPE[$this->business_service_type];
    }


}
