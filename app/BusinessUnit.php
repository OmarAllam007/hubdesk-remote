<?php

namespace App;

use App\Behaviors\Listable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use KGS\BusinessDocumentsFolder;
use KGS\Document;
use KGS\DocumentNotification;
use KGS\KGSBusinessUnit;

/**
 * App\BusinessUnit
 *
 * @property integer $id
 * @property string $name
 * @property integer $location_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Location $location
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereLocationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BusinessUnit selection($empty = false)
 * @mixin \Eloquent
 */
class BusinessUnit extends KModel
{
    use Listable;

    protected $fillable = ['code', 'name', 'location_id', 'logo', 'business_unit_bgd', 'division_id'];

//    protected $appends = ['bu_roles'];


    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
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


    public function scopeKGS(Builder $query){
        $kgsIds = KGSBusinessUnit::pluck('business_unit_id')->toArray();
        $query->orderBy('name')->whereIn('id', $kgsIds);

        return $query;
    }
    public function categories()
    {
        return $this->hasMany(Category::class, 'business_unit_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(BusinessUnitRole::class);
    }

    public function getBuRolesAttribute()
    {
        return $this->roles;
    }


    public static function uploadAttachment(BusinessUnit $businessUnit, UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();

        $folder = storage_path('app/public/attachments/business_unit/' . $businessUnit->id . '/');
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $path = $folder . $filename;
        if (is_file($path)) {
            $filename = uniqid() . '_' . $filename;
            $path = $folder . $filename;
        }

        $file->move($folder, $filename);

        $final_path = '/attachments/business_unit/' . $businessUnit->id . '/' . $filename;

        return $final_path;
    }

    public function getUrlAttribute()
    {
        $basename = str_replace('+', ' ', urlencode(basename($this->logo)));
        $dirname = dirname($this->logo);
        $path = $dirname . '/' . $basename;
        return url('/storage' . $path);
    }

    function documents()
    {
        return $this->hasMany(Document::class);
    }

    function document_roles()
    {
        return $this->belongsToMany(User::class, 'bu_documents_roles');
    }

    function document_notifications()
    {
        return $this->hasMany(DocumentNotification::class);
    }

    function canDisplay()
    {
        /** @var Category $category */
        foreach ($this->categories as $category) {
            if ($category->canDisplay(\App\ServiceUserGroup::$CATEGORY)) {
                return true;
            }
        }
        return false;
    }

    function business_folders()
    {
        return $this->hasMany(BusinessDocumentsFolder::class);
    }

    function business_unit_notification_levels()
    {
        return $this->hasMany(DocumentNotification::class);
    }

    function isExceedNoOfLimitedTickets(Category $category, Subcategory $subcategory = null, Item $item = null, SubItem $subItem = null)
    {
        $is_exceed = false;

        $firstDay = Carbon::create('first day of this month')->format('Y-m-d 00:00:00');
        $lastDay = Carbon::create('last day of this month')->format('Y-m-d 11:59:59');


        $category_limit = $category->limitations()->whereJsonContains('value', ["$this->id"])->first();

        $category_current_month_tickets = Ticket::whereHas('requester', function ($query) {
            $query->where('business_unit_id', $this->id);
        })->whereBetween('created_at', [$firstDay, $lastDay])->where('category_id', $category->id)->count();

        if ($category_limit && $category_current_month_tickets >= $category_limit->number_of_tickets) {
            return $is_exceed = true;
        }


        if ($subcategory) {
            $subcategory_limit = $subcategory->limitations()->whereJsonContains('value', ["$this->id"])
                ->first();

            $subcategory_current_month_tickets = Ticket::whereHas('requester', function ($query) {
                $query->where('business_unit_id', $this->id);
            })->whereBetween('created_at', [$firstDay, $lastDay])->where('subcategory_id', $subcategory->id)->count();

            if ($subcategory_limit && $subcategory_current_month_tickets >= $subcategory_limit->number_of_tickets) {
                return $is_exceed = true;
            }
        }


        if ($item) {
            $item_limit = $item->limitations()->whereJsonContains('value', ["$this->id"])
                ->first();

            $item_current_month_tickets = Ticket::whereHas('requester', function ($query) {
                $query->where('business_unit_id', $this->id);
            })->whereBetween('created_at', [$firstDay, $lastDay])->where('item_id', $item->id)->count();

            if ($item_limit && $item_current_month_tickets >= $item_limit->number_of_tickets) {
                return $is_exceed = true;
            }
        }

        if ($subItem) {
            $subItem_limit = $subItem->limitations()->whereJsonContains('value', ["$this->id"])
                ->first();

            $subItem_current_month_tickets = Ticket::whereHas('requester', function ($query) {
                $query->where('business_unit_id', $this->id);
            })->where('subitem_id', $subItem->id)->whereBetween('created_at', [$firstDay, $lastDay])->count();

            if ($subItem_limit && $subItem_current_month_tickets >= $subItem_limit->number_of_tickets) {
                return $is_exceed = true;
            }
        }


        return $is_exceed;
    }
} 
