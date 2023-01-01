<?php


namespace App\Helpers;


use App\SapUser;
use App\User;
use App\UserProcess;
use Carbon\Carbon;
use Laminas\Soap\Client;
use Laminas\Validator\Date;

class SapApi
{
    protected $user;
    public $sapUser;
    private $result;

    public function __construct($user)
    {
        $this->user = $user;
    }


    private function connectToSAP()
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
            $this->result = $client->ZHCM_PAYROLL_TECH_V2(['IM_PERNR' => $this->user->employee_id]);

        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    private function processFiles()
    {
        if (!isset($this->result->PDF)) {
            return false;
        }


        if (!isset($this->result->PDF->item)) {
            return false;
        }

        $files = collect();
        $fileIndex = 1;
        $filesData = $this->result->PDF->item;


        foreach ($filesData as $key => $pdfFile) {

            $fileIndex++;
            $keyName = $pdfFile->MEMORY;

            if (!isset($files[$keyName])) {
                $files[$keyName] = null;
            }

            foreach ($pdfFile->PDF->item as $keyb => $item) {

                $files[$keyName] .= $item->LINE;
            }
        }

        return $files;
    }


    public function getSalarySlip()
    {
        $condition = $this->checkIfAlreadyGeneratedToday();
        if ($condition) {
            for ($i = 1; $i <= 6; $i++) {
                $filename = $this->user->employee_id . '_salaryPDF0' . $i . '.pdf';
                $fileUrls[] = 'salary_slip/' . $filename;
            }
            return $fileUrls;
        } else {
            $this->connectToSAP();
            $files = $this->processFiles();
            return $this->getFilesUrls($files);
        }
    }

    public function getFilesUrls(\Illuminate\Support\Collection $files): array
    {
        $folder = storage_path('app/public/salary_slip/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $fileUrls = [];

        foreach ($files as $key => $file) {
            $filename = $this->user->employee_id . '_salary' . $key . '.pdf';
            $path = $folder . $filename;

            if (is_file($path)) {
                $path = $folder . $filename;
            }


            file_put_contents($path, $file);
            $fileUrls[] = 'salary_slip/' . $filename;
        }

        $this->updateLAstGeneratedDate();

        return $fileUrls;
    }

    private function checkIfAlreadyGeneratedToday()
    {
        $lastGenerate = auth()->user()->last_generated_payslip;

        if ($lastGenerate) {
            $isLastToday = Carbon::today()->diffInDays($lastGenerate->last_payslip_generation) == 0;

            if ($isLastToday) {
                return true;
            }

            return false;
        }
    }

    private function updateLAstGeneratedDate()
    {
        $userGenerate = UserProcess::where('employee_id', auth()->user()->employee_id)->first();

        if (!$userGenerate) {
            UserProcess::create([
                'employee_id' => auth()->user()->employee_id,
                'last_payslip_generation' => Carbon::now(),
            ]);
        } else {
            $userGenerate->update([
                'employee_id' => auth()->user()->employee_id,
                'last_payslip_generation' => Carbon::now(),
            ]);
        }
    }



    public function getUserInformation()
    {
        $employeeInformation = '';

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