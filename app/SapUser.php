<?php


namespace App;


use App\Helpers\LetterSponserMap;
use Carbon\Carbon;

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


    function calculateEOS($amount, $dateOfJoin)
    {
        $fromCarbonDate = Carbon::parse($dateOfJoin);
        $difference = 0;

        if ($fromCarbonDate) {

            $difference = $fromCarbonDate->floatDiffInYears(Carbon::now());

            if ($difference < 2) {
                return 0;
            } elseif ($difference >= 2 && $difference <= 5) {
                return $amount / 3;
            } elseif ($difference >= 5 && $difference <= 10) {
                return $amount * (2 / 3);
            }

            return $amount;
        }
    }

    function convertSapDataToArray()
    {
        $sap_data = [];

        foreach ($this->sapData as $key => $data) {
            $sap_data[$key] = $data;
        }

        $this->sapData = $sap_data;

        list($en_occupation, $ar_occupation) = $this->getOccupationTranslation();
        $this->sapData = [
            'employee_id' => $this->sapData['PERNR'],
            'system_position' => $this->sapData['POSITION'],
            'occupation' => $ar_occupation,
            'en_occupation' => $en_occupation,
            'en_name' => $this->sapData['ENAME'],
            'ar_name' => $this->sapData['SNAME'] != '' ? $this->sapData['SNAME'] : $this->sapData['ENAME'],
            'en_nationality' => $this->sapData['NATIO_E'],
            'ar_nationality' => $this->sapData['NATIO_A'],
            'iqama_number' => $this->sapData['ICNUM_IQAMA'] != '' ? $this->sapData['ICNUM_IQAMA'] : $this->sapData['ICNUM_NATID'],
            'passport_number' => $this->sapData['ICNUM_PASS'],
            'sponsor_id' => $this->sapData['SPONR'],
            'sponsor_company' => LetterSponserMap::$sponsers[$this->sapData['SPONR']],
            'en_sponsor_company' => $this->sapData['SPONN'],
            'allowances' => $this->getAllowances(),
            'total_package' => $this->sapData['total_package'],
            'date_of_join' => $this->sapData['DAT01'],
            'iban' => $this->sapData['IBAN'],
            'department' => '',
            'job_status' => '',
            'work_contract' => '',
            'discounts' => '',
            'phone' => '',
            'fax' => '',
            'education' => $this->sapData['ZZEDUCATION'],
            'is_active' => $this->sapData['BEGDA'] == '0000-00-00',
            'eos_amount' => $this->calculateEOS($this->sapData['EOS_AMT'], $this->sapData['DAT01']),
            'is_saudi'=> str_starts_with($this->sapData['ICNUM_NATID'],'10')
        ];

        if (in_array($this->sapData['sponsor_id'], [7013614099, 7014784685, 7015080299])) {
            $this->sapData['sponsor_company'] = 'شركة الكفاح القابضة';
        }

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
//        $this->sapData['fixed_amount'] = 100;
        return $allowances;
    }

    /**
     * @return array
     */
    public function getOccupationTranslation(): array
    {
        $en_occupation = '';
        $ar_occupation = '';

        if (preg_match('/[A-Za-z0-9]/', $this->sapData['VCTXT'])) {
            $jobMap = LetterJobMap::where('en_name', $this->sapData['VCTXT'])->first();
            if ($jobMap) {
                $en_occupation = $jobMap->en_name;
                $ar_occupation = $jobMap->ar_name;
            }
        } else {
            $jobMap = LetterJobMap::where('ar_name', $this->sapData['VCTXT'])->first();
            if ($jobMap) {
                $en_occupation = $jobMap->en_name;
                $ar_occupation = $jobMap->ar_name;
            }
        }
        return array($en_occupation, $ar_occupation);
    }


    function getAllowancesString()
    {
        $str = '';

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('housing_allowance')) {
            $str .= ' ، بدل السكن ' . $this->sapData['allowances']['housing_allowance'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('transportation_allowance')) {
            $str .= '،  بدل النقل ' . $this->sapData['allowances']['transportation_allowance'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('nature_of_work_allowance')) {
            $str .= ' ، بدل طبيعة عمل ' . $this->sapData['allowances']['nature_of_work_allowance'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('mobile_allowance')) {
            $str .= ' ، بدل هاتف ' . $this->sapData['allowances']['mobile_allowance'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('fixed_overtime')) {
            $str .= '، بدل دوام إضافي ثابت ' . $this->sapData['allowances']['fixed_overtime'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('fixed_bonus')) {
            $str .= '، بدل إضافي ثابت ' . $this->sapData['allowances']['fixed_bonus'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('food_allowance')) {
            $str .= '، بدل طعام ثابت ' . $this->sapData['allowances']['food_allowance'] . " ريال ";
        }

        if (isset($this->sapData['allowances']) && collect($this->sapData['allowances'])->has('clothes_allowance')) {
            $str .= '، بدل ملابس  ' . $this->sapData['allowances']['clothes_allowance'] . " ريال ";
        }

        return $str;
    }
}