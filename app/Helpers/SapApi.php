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
            $result = $client->ZHCM_PAYROLL_TECH(['IM_PERNR' => $this->user->employee_id]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }

        if (!isset($result->EX_XPDF->item)) {
            return false;
        }

        $file = null;

        foreach ($result->EX_XPDF->item as $item) {
            $file .= $item->LINE;
        }

        $folder = storage_path('app/public/attachments/salary_slip/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $filename = $this->user->employee_id . '_salary' . '.pdf';
        $path = $folder . $filename;

        if (is_file($path)) {
            $path = $folder . $filename;
        }

        file_put_contents($path, $file);

        return '/attachments/salary_slip/' . $filename;
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