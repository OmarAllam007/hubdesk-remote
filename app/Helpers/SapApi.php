<?php


namespace App\Helpers;


use App\SapUser;
use Laminas\Soap\Client;

class SapApi
{
    protected $user;
    public $sapUser;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getSalarySlip()
    {
        $url = env('ZHCM_PAYROLL_TECH_URL');

        if (!$url || !$this->user->employee_id) {
            return false;
        }

        $client = new Client();
        $client->setUri($url);
        $client->setOptions([
            'soap_version' => SOAP_1_2,
            'wsdl' => $url,
            'login' => env('SAP_USER'),
            'password' => env('SAP_PASS'),
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
        ]);

        try {
//            dd($client->getFunctions());
            $result = $client->ZHCM_PAYROLL_TECH_V2(['IM_PERNR' => $this->user->employee_id]);
        } catch (\Throwable $e) {
//            dd($e->getMessage());
            return $e->getMessage();
        }

//        dd('asdasd');
        if (!isset($result->PDF)) {
            return false;
        }

        $files = collect();
        $fileIndex = 1;

        $filesData = $result->PDF->item;

        foreach ($filesData as $key => $pdfFile) {

            $fileIndex++;
            foreach ($pdfFile->PDF->item as $item) {
                if (!isset($files[$pdfFile->MEMORY])) {
                    $files[$pdfFile->MEMORY] = null;
                }
                $files[$pdfFile->MEMORY] .= $item->LINE;
            }
        }


        $folder = storage_path('app/public/attachments/salary_slip/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $fileUrls = [];
        foreach ($files as $key=>$file) {
            $filename = $this->user->employee_id . '_salary'. $key. '.pdf';
            $path = $folder . $filename;

            if (is_file($path)) {
                $path = $folder . $filename;
            }


            file_put_contents($path, $file);
            $fileUrls[] = '/attachments/salary_slip/' . $filename;
        }

        return $fileUrls;
    }


    public function getUserInformation()
    {
        $employeeInformation = '';
        //TODO: not completed
        $url = env('ZHCM_LETTERS_TECH_URL');

        if (!$url || !$this->user->employee_id) {
            return false;
        }

        $client = new Client();
        $client->setUri($url);
        $client->setOptions([
            'soap_version' => SOAP_1_2,
            'wsdl' => $url,
            'login' => env('SAP_USER'),
            'password' => env('SAP_PASS'),
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
        ]);


        try {
            $result = $client->ZHR_HUBDESK_DATA_LETTERS(['IM_PERNR' => $this->user->employee_id]);
            $this->sapUser = new SapUser($result->IT_DATA->item);

        } catch (\Throwable $e) {
            return $e->getMessage();
        }


        return $employeeInformation;
    }
}