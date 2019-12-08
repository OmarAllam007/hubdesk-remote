<?php

namespace KGS;

use App\BusinessUnit;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['folder_id', 'name', 'start_date', 'end_date', 'last_updated_by', 'path'];

    protected $dates = ['start_date', 'end_date'];



    function folder()
    {
        return $this->belongsTo(BusinessDocumentsFolder::class);
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
}
