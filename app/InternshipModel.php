<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternshipModel extends Model
{
    static $en_cities = [
        'No preference', 'Arar', 'Dubai', 'Dammam', 'Hail', 'Hafer Al-Baten', 'Hofuf',
        'Al Hasa', 'Al-Jubail', 'Jeddah', 'Jazan', 'Al-Khobar',
        'Khamis', 'Al-Madinah', 'Al-Nariyah', 'Al-OYEON', 'Qatif',
        'Al-Riyadh', 'Sakaka', 'Summan'
    ];

    static $ar_cities = [
        'أي مدينة', 'عرعر', 'دبي', 'دبي', 'حائل', 'حفر الباطن', 'الهفوف',
        'الأحساء', 'الجبيل', 'جدة', 'جيزان', 'الخبر',
        'خميس مشيط', 'المدينة المنورة', 'النعيرية', 'العيون', 'القطيف',
        'الرياض', 'سكاكا'
    ];


    static $businessUnits = [
        'No preference', 'Al-Kifah Contracting (KCC)', 'Al-Kifah Precast Co (KPC)', 'Al-Kifah Ready Mix & Blocks (KRB)',
        'Al-Kifah Logistics (KILO)', 'Al-Kifah Equipments (KICE)', 'Tamweel ALOula',
        'Al-Kifah Real Estates (KRE)', 'Al Motaweroon', 'Al-Kifah Farms', 'Al Afaleq Consulting',
        'IMDAD (Al Kifah Catering)', 'Hubtech (Madar Technology)', 'Al-Kifah Advertising Agency (KAF)',
        'Al-Kifah Travel Agency (KTA)', 'AlQuwa', 'Al-Kifah Private Schools', 'Al-Kifah Paper Products',
        'Al-Kifah Commercial Printing', 'Al-Kifah Holding Company', 'Enar Renewables Company'
    ];

    static $interestedIn = [
        1 => 'Internship (with stipend only)',
        'Internship (without stipend)',
        'No preference'
    ];

    static $workPreference = [
        1 => 'Office-based',
        'Field-based',
        'Remote Work (work from home)',
        'No Preference (will be happy to take whatever is available)'
    ];

    static $degreeType = [
        1 => 'Diploma',
        'Bachelor'
    ];
}
