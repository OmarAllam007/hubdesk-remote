<?php

namespace App\Helpers;

use ChromeDevtoolsProtocol\Context;
use ChromeDevtoolsProtocol\Instance\Launcher;
use ChromeDevtoolsProtocol\Model\Page\NavigateRequest;
use ChromeDevtoolsProtocol\Model\Page\PrintToPDFRequest;
use Exception;
use Spatie\Browsershot\Browsershot;

class ChromePrint
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    function print()
    {
        // Sometimes an exception is thrown on cleaning up after generating the file
        // We use retry here to repeat the operation if the exception is thrown to ensure generating the file
        return retry(3, function () {
            $url = $this->file;
            if (substr($this->file, 0, 4) !== 'http') {
                $url = "file://$this->file";
            }
//            $file = storage_path('app/' . ('print_request_') . '.pdf');
            $file = storage_path('app/print_request.pdf');

            Browsershot::url($url)
                ->waitUntilNetworkIdle()
                ->setDelay(3000)
                ->showBackground()
                ->windowSize(1024, 720)
                ->timeout(300)
                ->margins(0.5, 0.5, 0.5, 0.5, 'in')
//                ->landscape(true)
                ->format('A4')->savePdf($file);

            if ($file && file_exists($file)) {
                return $file;
            }

            return false;
        }, 1000);
    }
}