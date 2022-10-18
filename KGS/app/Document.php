<?php

namespace KGS;

use App\BusinessUnit;
use App\DocumentRequirements;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon end_date
 */
class Document extends Model
{
    protected $fillable = ['folder_id', 'name', 'start_date', 'end_date',
        'last_updated_by', 'path', 'level', 'level_id','remarks','notify_duration'];

    protected $dates = ['start_date', 'end_date'];


    function folder()
    {
        return $this->belongsTo(BusinessDocumentsFolder::class);
    }

    function requirements(){
        return $this->hasMany(DocumentRequirements::class);
    }


    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    function getLastUpdatedAttribute()
    {
        if ($this->last_updated_by) {
            return User::find($this->last_updated_by);
        }
        return '';
    }

    public function getUrlAttribute()
    {
        $basename = str_replace('+', ' ', urlencode(basename($this->path)));
        $dirname = dirname($this->path);
        $path = $dirname . '/' . $basename;
        return url('/storage/' . $path);
    }

    function notifications()
    {
        return $this->hasMany(KGSLog::class);
    }

    function shouldNotified($level)
    {

        $notified_before = KGSLog::where('document_id', $this->id)
            ->where('type', KGSLog::NOTIFICATION_TYPE)
            ->where('level_id', $level->id)
            ->exists();

        if ($this->end_date->diffInDays(now()) <= $level->days && !$notified_before) {
            return true;
        }


        return false;
    }

    function markAsShouldRenew()
    {
        $level = DocumentNotification::where('business_unit_id', $this->folder->business_unit->id)->first();
        if(!$level){
            return false;
        }

        $isExpired = $this->end_date->lessThanOrEqualTo(Carbon::now()) || ($this->end_date->diffInDays(now()) <= $level->days);

        if ($isExpired) {
            return true;
        }

        return false;
    }

    public function getDirtyOriginals()
    {
        if (!$this->isDirty()) {
            return [];
        }

        $attributes = [];
        $updated = array_keys($this->getDirty());

        foreach ($updated as $item) {
            $attributes[$item] = $this->getOriginal($item);
        }

        return $attributes;
    }

    function logs()
    {
        return $this->hasMany(KGSLog::class);
    }

    function getRemainingDaysAttribute()
    {
        return \Carbon\Carbon::now()->diffInDays($this->end_date, false);
    }

    function getWarningColorAttribute()
    {
        if ($this->remaining_days < 60 && $this->remaining_days > 0) {
            return 'danger';
        }
        return 'deep_danger';
    }

    function getLevelIdStrAttribute()
    {
        if(!$this->level){
            return;
        }
        return mb_strtolower(substr($this->level, strrpos($this->level, '\\') + 1)) . '_id';
    }

    function getLevelNameStrAttribute()
    {
        if(!$this->level){
            return;
        }
        $level = app($this->level)->where('id', $this->level_id)->first();
        return $level->level_arrow_name ?? $level->name;
    }
}
