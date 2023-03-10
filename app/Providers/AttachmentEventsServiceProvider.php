<?php

namespace App\Providers;

use App\Attachment;
use Illuminate\Support\ServiceProvider;

class AttachmentEventsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Attachment::saving(function (Attachment $attachment) {
            if (!$attachment->path) {
                $file = $attachment->uploadedFile();
                $filename = $file->getClientOriginalName();

                $folder = storage_path('app/public/attachments/'.$attachment->ticket_id.'/');
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }

                $path = $folder . $filename;
                if (is_file($path)) {
                    $filename = uniqid() . '_' . $filename;
                    $path = $folder . $filename;
                }
                try {

                $file->move($folder, $filename);
                }catch (\Exception $e){
                    throw $e;
                }

                $attachment->path = '/attachments/'.$attachment->ticket_id.'/'.$filename;
            }
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
