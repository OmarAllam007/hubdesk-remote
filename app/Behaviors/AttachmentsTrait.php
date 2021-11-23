<?php


namespace App\Behaviors;


use App\Attachment;

trait AttachmentsTrait
{

    private function uploadTicketAttachments($ticket, $files)
    {
        Attachment::flushEventListeners();

        if (!empty($files)) {
//            dd($files);
            foreach ($files as $file) {

                $filename = $file->getClientOriginalName();
                $folder = storage_path('app/public/attachments/' . $ticket->id . '/');
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }

                $path = $folder . $filename;
                if (is_file($path)) {
                    $filename = uniqid() . '_' . $filename;
                    $path = $folder . $filename;
                }

                $file->move($folder, $filename);


                Attachment::create([
                    'type' => Attachment::TICKET_TYPE,
                    'reference' => $ticket->id,
                    'path' => '/attachments/' . $ticket->id . '/' . $filename,
                ]);

            }
        }
    }
}