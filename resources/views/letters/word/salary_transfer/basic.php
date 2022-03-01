<?php

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';


$titleStyle = ["size" => 14, 'rtl' => true];
$rightStyle = ['align' => 'right'];

$section = $phpWord->addSection(['marginTop' => 2400,
    'marginLeft' => 200, 'marginRight' => 400, 'marginBottom' => 400]);

$section->addText(
    'التاريخ: ' . $letterTicket->last_approval_date, ['size' => 14], $rightStyle);
$section->addText('', [], []);


$section->addText('', [], []);
$section->addText("الســادة / {$user['sponsor_company']}       المحترمين", ['size' => 14], $rightStyle);

$section->addText('', [], []);

$section->addText(' ،،، السلام عليكم ورحمة الله وبركاته ،،، وبعد', ['size' => 14], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText("أفيد سيادتكم برغبتي الاستفادة من خدمات ({$letterTicket->letter->ar_name}) ،  ولهذا أرغب بأن يتم صرف راتبي الشهري إلى ( {$letterTicket->letter->ar_name})
", ['size' => 14, 'rtl' => true], $rightStyle);

$section->addText("حساب رقم ( {$user['iban']} ) كما أرجو في حال انتهاء خدماتي لأي سبب كان بأن يتم تحويل كافة مستحقاتي إلى حسابي المشار أعلاه وأن لا يتم الإجراء إلا بخطاب موجه لكم من البنك المذكور .",
    ['size' => 14, 'rtl' => true],
    $rightStyle);

$section->addText('', ['size' => 14], $rightStyle);
$section->addText(':الإسم', ['size' => 14], $rightStyle);
$section->addText(':التوقيع', ['size' => 14], $rightStyle);
$section->addText('', ['size' => 14], $rightStyle);
$section->addLine(['weight' => 1, 'width' => 1000, 'height' => 0, 'positioning' => 'absolute', 'color' => 'grey']);


$section->addText('', [], []);
$section->addText("الســادة / {$letterTicket->letter->ar_name}       المحترمين", ['size' => 14], $rightStyle);

$section->addText('', [], []);
$section->addText(' ،،، السلام عليكم ورحمة الله وبركاته ،،، وبعد', ['size' => 14], $rightStyle);
$section->addText('', [], []);

$section->addText(" الموضوع رواتب ومستحقات موظفنا السيد : {$user['ar_name']} حيث أن المذكور - {$user['ar_nationality']} - الجنسية", ['size' => 14], $rightStyle);
$section->addText("یعمل لدینا قد أخطرنا بأنه یرغب بحصوله على تمویل شخصي منكم ویطیب لنا في ذلك الصدد أن نقوم بتحویل رواتبه الشھریة إلیكم في تواریخ
 استحقاقھا وأن نقوم كذلك في حال انتھاء خدماته نتعھد بإبلاغكم خطیاً ومن ثم تحویل جمیع مستحقاته النظامیة إلیكم وتستمر
تعھداتنا المدرجة ھنا نافذة وساریة المفعول لحین انتھاء خدمة المذكور لدینا واستلامنا أشعار خطي منكم بإعفائنا من التزاماتنا
.الواردة أعلاه علماً بأنه مازال یعمل لدینا حتى تاریخه", ['size' => 14], $rightStyle);

$section->addText("", ['size' => 14], $rightStyle);


$section->addText('', [], ['spacing' => 1000]);
$section->addText($user['sponsor_company'], [], []);
$section->addText('', [], ['spacing' => 150]);
$section->addText(config('letters.signature_name'), [], []);

$section->addPageBreak();


$headerStyle = ['bold' => true, "size" => 14, 'underline' => 'single'];
$titleStyle = ["size" => 14, 'rtl' => true];
$rightStyle = ['align' => 'right'];


$section->addText("التاريخ : " . $letterTicket->last_approval_date . " م", [], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText("الســادة / {$letterTicket->letter->ar_name}                        المحترمين", $titleStyle, $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText(' ،،، السلام عليكم ورحمة الله وبركاته ،،، وبعد', ['size' => 14], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText("نفيدكم بأن السيد / {$user['ar_name']} ، الجنسية / {$user['ar_nationality']}، هوية رقم/{$user['iqama_number']}،", ['size' => 14, 'rtl' => true], $rightStyle);


$foodAllowances = isset($user['allowances']['food_allowance']) ? "بدل طعام({$user['allowances']['food_allowance']} ريال)" : '';
$typeWorkAllowance = isset($user['allowances']['nature_of_work_allowance']) ? "بدل طبيعة عمل ({$user['allowances']['nature_of_work_allowance']} ريال)" : '';
$fixedAllowance = isset($user['allowances']['fixed_amount']) ? "بدل ثابت ({$user['allowances']['fixed_amount']} ريال)" : '';
$housing_allowance = isset($user['allowances']['housing_allowance']) ? "بدل سكن ({$user['allowances']['housing_allowance']} ريال)" : '';
$transportation_allowance = isset($user['allowances']['transportation_allowance']) ? "بدل نقل ({$user['allowances']['transportation_allowance']} ريال)" : '';
$fixed_overtime = isset($user['allowances']['fixed_overtime']) ? "بدل إضافي ثابت ({$user['allowances']['fixed_overtime']} ريال)" : '';
$fixed_bonus = isset($user['allowances']['fixed_bonus']) ? "مكافأة ثابتة  ({$user['allowances']['fixed_bonus']} ريال)" : '';

$section->addText("يعمل لدينا من تاريخ: {$user['date_of_join']} م بوظيفة: {$user['occupation']} ، ويتقاضى الراتـب اســاسي ({$user['allowances']['basic_salary']} ريال)،", ['size' => 14, 'rtl' => true], ['align' => 'right', 'rtl' => 'true']);

$lastParagraph = $letterTicket->allownaces_string ."بإجمالي قدره ( {$user['total_package']} .ريـال) ، ";
$lastParagraph .= "مستحقات نهاية الخدمة (حتى تاريخه): {$user['eos_amount']} ريال";
$lastParagraph .= " وقد أصدر ھذا الخطاب بناء على طلب الموظف لتقدیمه إلى إدارتكم دون أدنى مسئولیة على الشركة أو منسوبیھا  ";

$section->addText("$lastParagraph", ['size' => 14, 'rtl' => true], ['align' => 'right', 'rtl' => 'true']);

$section->addText('', [], ['spacing' => 1000]);
$section->addText('ولكم وافر الشكر والتقدير ،،،', ['bold' => true, 'size' => 18, 'rtl' => true], ['align' => 'center']);
$section->addText('', [], ['spacing' => 1000]);
$section->addText($user['sponsor_company'], [], []);
$section->addText('', [], ['spacing' => 150]);
$section->addText(config('letters.signature_name'), [], []);

//require  resource_path().'/views/letters/word/bank/general_bank.php';

require resource_path() . '/views/letters/word/_footer.php';