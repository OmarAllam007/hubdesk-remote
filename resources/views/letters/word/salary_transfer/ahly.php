<?php

$phpWord = new \PhpOffice\PhpWord\PhpWord();


$IbanField = $letterTicket->ticket->fields->first() ? $letterTicket->ticket->fields->first()->value : '';


$titleStyle = ["size" => 14, 'rtl' => true];
$rightStyle = ['align' => 'right'];

$section = $phpWord->addSection(['marginTop' => 2400,
    'marginLeft' => 200, 'marginRight' => 400, 'marginBottom' => 400]);


//$section->addPageBreak();


$headerStyle = ['bold' => true, 'underline' => 'single', 'rtl' => 'right'];
$titleStyle = ['rtl' => true];
$rightStyle = ['align' => 'right'];


/** @var \App\LetterTicket $letterTicket */


$tableStyle = array(
    'borderColor' => 'fff',
    'borderSize' => 6,
    'cellMargin' => 50,
    'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
    'width' => 100 * 50,
    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
    'rtl' => true
);
//$firstRowStyle = array('bgColor' => 'faf3ed');
$phpWord->addTableStyle('AhlyTable', $tableStyle, []);
$table = $section->addTable('AhlyTable');

$cellColor = 'fff';

$style1 = ['rtl' => true,];
$style2 = ['align' => 'right'];

$row1 = $table->addRow();
$englishCell = $row1->addCell(100 * 50, $style1);
$arabicCell = $row1->addCell(100 * 50, $style1);

$arabicCell->addText(" التاريخ : {$letterTicket->last_approval_date} ", [], $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("السادة: بنك الاهلي السعودي", [], $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("الموضوع: شهادة تحويل راتب", [], $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("نشهد بان المذكور ادناه يعمل لديها بنظام التفرغ الكامل وبناء على تفويض منه فسوف يتم تحويل صافي راتبه مع كامل الدبلات والعمولات الشهرية (إن وجدت) وجميع مستحقاته النهائية( شاملة مكافاة نهاية الخدمة واي بدلات بعد الاستقالة) في حالة الاستقالة او الفصل او التقاعد (لأي سبب كانت الاستقالة او الفصل او التقاعد)", [], $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("الى حسابه المصرفي لدى: البنك الاهلي التجاري", [], $style2);
$arabicCell->addText("رقم الحساب: {$user['iban']}", $style1, $style2);
$arabicCell->addText("رقم الحساب الدولي: {$user['iban']}", $style1, $style2);
$arabicCell->addText("كما تلتزم الشركة بعدم السماح للموظف بالغاء او تعديل التفويض الا بموافقة البنك الخطية، هذا بالاضافة انه في حالة استقالة الموظف او فصله او تقاعده فان الشركة تلتزم باخطار البنك الاهلي السعودي (ادارة التحصيل بحي الخالدية بجدة) او بواسطة البريد الالكتروني CollectionsSkipThacing@alahli.com
                        بتاريخ ايداع مستحقاته النهائية.", $style1, $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("كما ان الشركة لا تتحمل اي التزام قانوني او مالي بخلاف ما ذكر اعلاه", $style1, $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("اسم الموظف: {$user['ar_name']}", [], $style2);
$arabicCell->addText("رقم الهوية: {$user['iqama_number']}", [], $style2);
$arabicCell->addText("تاريخ التعيين: {$user['date_of_join']}", [], $style2);
$arabicCell->addText("المسمى الوظيفي: {$user['occupation']}", [], $style2);
$arabicCell->addText("صافي الراتب الشهري: {$user['total_package']} ريال", [], $style2);
$arabicCell->addText("تاريخ ايداعه: في اليوم الاول من كل شهر", [], $style2);
$arabicCell->addText("", [], $style2);
$arabicCell->addText("{$user['sponsor_company']}", ['align'=>'middle'], ['align'=>'center']);

$englishCell->addText("Date : {$letterTicket->last_approval_date}");
$arabicCell->addText("", [], $style2);
$englishCell->addText("M/S: NCB");
$arabicCell->addText("", [], $style2);
$englishCell->addText("Subject: Salary Transfer Certificate");
$arabicCell->addText("", [], $style2);
$englishCell->addText("This is to certify that the below mentioned is our full time employee and based on authorization from him we will transfer his net monthly salary along with all other allowances and commissions (if any) regularly on a monthly basis to: and shall also deposit his final settlement including End of Service Benefits and any other allowances in case of his resignation, retirement or termination due to any reason in to his bank account at: Bank: NCB");
$arabicCell->addText("", [], $style2);
$englishCell->addText("A/C No: {$user['iban']}");
$englishCell->addText("IBAN NO: {$user['iban']}");
$englishCell->addText("Also the company certifies that it will not cancel this undertaking unless a written clearance from Bank is received, moreover, in case of resignation / retirement/ termination, the company will immediately inform NCB's collections Department at Al- Khaldiya, Jeddah P.O box 19396 Jeddah 21435 or by e-mail CollectionsSkipThacing@alahli.com about the date of deposit of final settlement of this employee. In this regard, the company is not responsible for any legal or financial commitment except what is mentioned above.");
$arabicCell->addText("", [], $style2);
$englishCell->addText("Employee Name: {$user['en_name']}");
$englishCell->addText("ID NO: {$user['iqama_number']}");
$englishCell->addText("Hire Date: {$user['date_of_join']}");
$userJob = str_replace("&","",$user['en_occupation']);
$englishCell->addText("Job description: {$userJob}");
$englishCell->addText("Net monthly Salary: {$user['total_package']} SR");
$englishCell->addText("Salary Deposit Date: First day of every month.");
$arabicCell->addText("", [], $style2);
$arabicCell->addText("", [], $style2);
$englishCell->addText(\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']],['align'=>'middle'], ['align'=>'center']);

$section->addText('', [], ['spacing' => 1000]);
$section->addText($user['sponsor_company'], ['rtl' => true], []);
$section->addText('', [], ['spacing' => 150]);
$section->addText(config('letters.signature_name'), ['rtl' => true], []);

/**
 * @param \PhpOffice\PhpWord\Element\Table $table
 * @param $text1
 * @param $text2
 */
function addNewRow($table, $text1, $text2, $color = 'fff', $gridSpan = 2)
{
    $row1 = $table->addRow();
    $row1->addCell(100 * 50, ['bgColor' => $color, 'vMerge' => 'restart'])
        ->addText($text1, [], ['align' => 'right']);
    $row1->addCell(100 * 50, ['bgColor' => $color, 'vMerge' => 'restart'])
        ->addText($text2, [], ['align' => 'right']);
}

require resource_path() . '/views/letters/word/_footer.php';