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
        $url = config('sap.ZHCM_PAYROLL_TECH_URL');

        if (!$url || !$this->user->employee_id) {
            return false;
        }

        $client = new Client();
        $client->setUri($url);
        $client->setOptions([
            'soap_version' => SOAP_1_2,
            'wsdl' => $url,
            'login' => config('sap.SAP_USER'),
            'password' => config('sap.SAP_PASS'),
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
            $keyName = $pdfFile->MEMORY;
            if (str_contains($pdfFile->MEMORY, '_')) {
                $keyName = substr($pdfFile->MEMORY, 0, strpos($pdfFile->MEMORY, "_"));
                $files[$keyName] = null;
            }
            if (!isset($files[$keyName])) {
                $files[$keyName] = null;
            }

            foreach ($pdfFile->PDF->item as $keyb=>$item) {

                $files[$keyName] .= $item->LINE;
            }
        }

        $folder = storage_path('app/public/salary_slip/');

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
            $fileUrls[] = 'salary_slip/' . $filename;
        }

        return $fileUrls;
    }


    public function getUserInformation()
    {
        $employeeInformation = '';
        //TODO: not completed
        $url = config('sap.ZHCM_LETTERS_TECH_URL');


        if (!$url || !$this->user->employee_id) {
            return false;
        }

        $client = new Client();
        $client->setUri($url);
        $client->setOptions([
            'soap_version' => SOAP_1_2,
            'wsdl' => $url,
            'login' => config('sap.SAP_USER'),
            'password' => config('sap.SAP_PASS'),
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