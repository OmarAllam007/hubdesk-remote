<?php

namespace App;

use App\Http\Requests\Request;
use Illuminate\Http\UploadedFile;

/**
 * @property string path
 * @property integer reference
 */
class Attachment extends KModel
{
    const TICKET_TYPE = 1;
    const TICKET_REPLY_TYPE = 2;
    const TICKET_APPROVAL_TYPE = 3;
    const TASK_TYPE = 4;

    protected $fillable = ['reference', 'type', 'path', 'created_at', 'updated_at'];

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * @param $type
     * @param $id
     * @param Request $request
     */
    public static function uploadFiles($type, $id, $request = null)
    {
        $files = !empty($request->attachments) ? $request->attachments : \Request::file('attachments');

        if ($files) {
            foreach ($files as $file) {
                if ($file) {
                    $attach = new static(['type' => $type, 'reference' => $id]);
                    $attach->uploadedFile($file);
                    $attach->save();
                }
            }
        }
    }

    public function uploadedFile(UploadedFile $file = null)
    {
        if ($file) {
            $this->uploadedFile = $file;
        }

        return $this->uploadedFile;
    }

    public function getDisplayNameAttribute()
    {
        return basename($this->path);
    }

    public function getTicketIdAttribute()
    {
        if ($this->type == self::TICKET_TYPE) {
            return $this->reference;
        }
        if($this->type == self::TASK_TYPE){
            return Ticket::find($this->reference)->request_id;
        }
        if ($this->type == self::TICKET_REPLY_TYPE) {
            return TicketReply::find($this->reference)->ticket_id;
        }

        if ($this->type == self::TICKET_APPROVAL_TYPE) {
            return TicketApproval::find($this->reference)->ticket_id;
        }
        return $this->reference->ticket_id;
    }

    public function getUploadedByAttribute()
    {
        if ($this->type == self::TICKET_TYPE) {
            $user = Ticket::find($this->reference)->created_by;
        } elseif ($this->type == self::TICKET_APPROVAL_TYPE) {
            $user = TicketApproval::find($this->reference)->created_by;
        }elseif ($this->type == self::TASK_TYPE) {
            $user = Ticket::find($this->reference)->created_by;
        } else {
            $user = TicketReply::find($this->reference)->user;
        }

        return $user->name;
    }

    public function getUrlAttribute()
    {
        $basename = str_replace('+', ' ', urlencode(basename($this->path)));
        $dirname = dirname($this->path);
        $path = $dirname . '/' . $basename;
        return url('/storage' . $path);
    }
}
