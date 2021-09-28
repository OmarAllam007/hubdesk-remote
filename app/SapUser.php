<?php


namespace App;


use App\Helpers\LetterSponserMap;

class SapUser
{
    protected $sapData;

    const salary_codes = [
        '1BAS' => 'basic_salary',
        '1CLA' => 'clothes_allowance',
        '1FBN' => 'fixed_bonus',
        '1FCM' => 'fixed_commission',
        '1FDA' => 'food_allowance',
        '1FIT' => 'fund_integrity_allowance',
        '1FOT' => 'fixed_overtime',
        '1HSA' => 'housing_allowance',
        '1MBA' => 'mobile_allowance',
        '1NWA' => 'nature_of_work_allowance',
        '1TRP' => 'transportation_allowance',
        '4BAS' => 'basic_salary',
        '4HOU' => 'housing_allowance',
        '4HSA' => 'housing_allowance',
        '4HSC' => 'housing_allowance',
        '4TAC' => 'transportation_allowance',
        '4TRP' => 'transportation_allowance'
    ];


    /**
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->sapData = $data;
        try {
            $this->sapData = $this->convertSapDataToArray();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    function getEmployeeSapInformation()
    {
        $this->sapData['allowances_str'] = $this->getAllowancesString();
        return $this->sapData;
    }

    function convertSapDataToArray()
    {
        $sap_data = [];

        foreach ($this->sapData as $key => $data) {
            $sap_data[$key] = $data;
        }
        $this->sapData = $sap_data;
        $job = LetterJobMap::where('en_name', $this->sapData['VCTXT'])->first();


        $this->sapData = [
            'employee_id' => $this->sapData['PERNR'],
            'system_position' => $this->sapData['POSITION'],
            'occupation' => $job ? $job->ar_name : '',
            'en_occupation' => $this->sapData['VCTXT'],
            'en_name' => $this->sapData['ENAME'],
            'ar_name' => $this->sapData['SNAME'] != '' ? $this->sapData['SNAME'] : $this->sapData['ENAME'],
            'en_nationality' => $this->sapData['NATIO_E'],
            'ar_nationality' => $this->sapData['NATIO_A'],
            'iqama_number' => $this->sapData['ICNUM_IQAMA'] != '' ? $this->sapData['ICNUM_IQAMA'] :$this->sapData['ICNUM_NATID'],
            'passport_number' => $this->sapData['ICNUM_PASS'],
            'sponsor_id' => $this->sapData['SPONR'],
            'sponsor_company' => LetterSponserMap::$sponsers[$this->sapData['SPONR']],
            'en_sponsor_company' => $this->sapData['SPONN'],
            'allowances' => $this->getAllowances(),
            'total_package' => $this->sapData['total_package'],
            'date_of_join' => $this->sapData['DAT01'],
            'iban' => $this->sapData['IBAN'],
            'department' => '',
            'eos_amount' => 0,
            'job_status' => '',
            'work_contract' => '',
            'discounts' => '',
            'phone' => '',
            'fax' => '',
            'education'=> $this->sapData['ZZEDUCATION'],
        ];
//        dd($this->sapData);

        return $this->sapData;
    }

    private function getAllowances()
    {
        $allowances = [];
        $total_package = 0;
        for ($i = 1; $i < 12; $i++) {
            $sap_data_key = $i < 10 ? $this->sapData["LGA0" . $i] : $this->sapData["LGA" . $i];
            $sap_data_value = $i < 10 ? $this->sapData["BET0" . $i] : $this->sapData["BET" . $i];

            if ($sap_data_key == '') {
                continue;
            }

            $total_package += $sap_data_value;

            $key = self::salary_codes[$sap_data_key];
            $allowances[$key] = number_format($sap_data_value);
        }
        $this->sapData['total_package'] = number_format($total_package);
        $this->sapData['fixed_amount'] = 100;
        return $allowances;
    }

    private function getOccupation($VCTXT)
    {
        if (!preg_match('/[^A-Za-z0-9]/', substr($VCTXT, 1, 1))) // '/[^a-z\d]/i' should also work.
        {
            return \App\Helpers\BusinessUnitMap::$jobs[$VCTXT];
        }

        return $VCTXT;
    }

    function getAllowancesString()
    {
        $str = '';

        if (collect($this->sapData['allowances'])->has('basic_salary')) {
            $str .= ' الراتب الأساسي ' . $this->sapData['allowances']['basic_salary'] . " ريال ";
        }
        if (collect($this->sapData['allowances'])->has('housing_allowance')) {
            $str .= ' ، بدل السكن ' . $this->sapData['allowances']['housing_allowance'] . " ريال ";
        }

        if (collect($this->sapData['allowances'])->has('transportation_allowance')) {
            $str .= '،  بدل النقل ' . $this->sapData['allowances']['transportation_allowance'] . " ريال ";
        }

        if (collect($this->sapData['allowances'])->has('nature_of_work_allowance')) {
            $str .= ' ، بدل طبيعة عمل ' . $this->sapData['allowances']['nature_of_work_allowance'] . " ريال ";
        }

        if (collect($this->sapData['allowances'])->has('mobile_allowance')) {
            $str .= ' ، بدل هاتف ' . $this->sapData['allowances']['mobile_allowance'] . " ريال ";
        }

        if (collect($this->sapData['allowances'])->has('fixed_overtime')) {
            $str .= '، بدل دوام إضافي ثابت ' . $this->sapData['allowances']['fixed_overtime'] . " ريال ";
        }

        if (collect($this->sapData['allowances'])->has('fixed_bonus')) {
            $str .= '، بدل إضافي ثابت ' . $this->sapData['allowances']['fixed_bonus'] . " ريال ";
        }

        if (collect($this->sapData['allowances'])->has('clothes_allowance')) {
            $str .= '، بدل ملابس  ' . $this->sapData['allowances']['clothes_allowance'] . " ريال ";
        }

        return $str;
    }
}