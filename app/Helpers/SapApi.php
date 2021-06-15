<?php


namespace App\Helpers;


use Laminas\Soap\Client;

class SapApi
{
    protected $user;

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
//        dd($url);

//        dd($this->user->employee_id);

        try {
        $result = $client->ZHCM_PAYROLL_TECH(['IM_PERNR' => $this->user->employee_id]);

        } catch (\Throwable $e) {
            return $e->getMessage();
        }

        if(!isset($result->EX_XPDF->item)){
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
}