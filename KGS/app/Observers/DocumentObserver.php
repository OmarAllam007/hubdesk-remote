<?php

namespace KGS\Observers;

use KGS\Document;
use KGS\KGSLog;

class DocumentObserver
{
    public function updating(Document $document)
    {
        if ($document->isDirty('end_date')) {
            KGSLog::where('type', KGSLog::NOTIFICATION_TYPE)
                ->where('document_id', $document->id)->delete();
        }

        $document->logs()->create([
            'level_id' => 0,
            'type' => KGSLog::UPDATE_DOCUMENT_TYPE,
            'user_id' => auth()->id(),
            'old_data' => $document->getDirtyOriginals(),
            'new_data' => $document->getDirty(),
        ]);
    }

    public function updated(Document $document)
    {

    }
}
