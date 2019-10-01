<?php


namespace App\Helpers;
use ChromeDevtoolsProtocol\Context;
use ChromeDevtoolsProtocol\Instance\Launcher;
use ChromeDevtoolsProtocol\Model\Page\NavigateRequest;
use ChromeDevtoolsProtocol\Model\Page\PrintToPDFRequest;
use Exception;

class ChromePrint
{
    public $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    function print()
    {
        // Sometimes an exception is thrown on cleaning up after generating the file
        // We use retry here to repeat the operation if the exception is thrown to ensure generating the file

        return retry(3, function () {
            $ctx = Context::withTimeout(Context::background(), 1800);
            $launcher = new Launcher();
            $launcher->setExecutable(config('app.chrome_path'));
            $instance = $launcher->launch($ctx);

            $tab = $instance->open($ctx);
            $tab->activate($ctx);
            $devtools = $tab->devtools();

            $page = $devtools->page();
            $page->enable($ctx);
            $page->navigate($ctx, NavigateRequest::builder()->setUrl("file://$this->file")->build());
            $page->awaitLoadEventFired($ctx);
            $request = PrintToPDFRequest::builder()->setDisplayHeaderFooter(false)
                ->setPrintBackground(true)
                ->setPreferCSSPageSize(true)
                ->build();

            $file = storage_path('app/' . uniqid('print_report_') . '.pdf');
            file_put_contents($file, base64_decode($page->printToPDF($ctx, $request)->data));

            $devtools->close();
            $instance->close();

            if ($file && file_exists($file)) {
                return $file;
            }

            return false;
        }, 1000);
    }
}