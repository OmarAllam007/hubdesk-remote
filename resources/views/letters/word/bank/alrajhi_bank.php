<?php

$phpWord = new \PhpOffice\PhpWord\PhpWord();


$section = $phpWord->addSection(['marginTop' => 2400,
    'marginLeft' => 200, 'marginRight' => 400, 'marginBottom' => 400]);
//$section->addText(
//    'HD: ' . $letterTicket->ticket->id
//);
//$section->addText('', [], []);
//$section->addText(
//    'ID: ' . $letterTicket->ticket->requester->employee_id
//);
//
//$section->addText('', [], []);
//$section->addText(
//    $letterTicket->ticket->requester->business_unit->name
//);

$headerStyle = ['bold' => true, "size" => 18, 'underline' => 'single', 'rtl' => 'right'];
$titleStyle = ["size" => 20, 'rtl' => true];
$rightStyle = ['align' => 'right'];


/** @var \App\LetterTicket $letterTicket */

$section->addText(" التاريخ : " . $letterTicket->last_approval_date . " م", [], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText('الموضوع : تعريف بالراتب', $headerStyle,
    ['align' => 'right']);
$section->addText('', [], []);
$section->addText("الســادة / {$letterTicket->letter->ar_name}                        المحترمين", $titleStyle, $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText(' السلام عليكم ورحمة الله وبركاته ', ['size' => 18], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);

$tableStyle = array(
    'borderColor' => 'fff',
    'borderSize' => 6,
    'cellMargin' => 50,
    'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
    'width' => 100 * 50,
    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
);
$firstRowStyle = array('bgColor' => 'faf3ed');
$phpWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);
$table = $section->addTable('myTable');

$cellColor = 'f2f2f2';
addNewRow($table, 'اسم الموظف', 'الجنسية', $cellColor);
addNewRow($table, $user['ar_name'], $user['ar_nationality']);
addNewRow($table, 'رقم إثبات الهوية', 'الرقم الوظيفي', $cellColor);
addNewRow($table, $user['iqama_number'], $letterTicket->ticket->requester->employee_id);
addNewRow($table, 'الوظيفة', 'تاريخ التعيين', $cellColor);
addNewRow($table, $user['occupation'], $user['date_of_join']);
addNewRow($table, 'الراتب الأساسي', 'إجمالي الراتب', $cellColor);
addNewRow($table, $user['allowances']['basic_salary'], $user['total_package']);

$table->addRow()->addCell(100 * 50, ['gridSpan' => 2])
    ->addText('تفاصيل البدلات', [], ['align' => 'right']);

$table->addRow()->addCell(100 * 50, ['gridSpan' => 2])
    ->addText("{$user['allowances_str']}" , [], ['align' => 'right', 'bidi' => false]);

$section->addText('', [], []);
$section->addText('', [], []);
$section->addText('كما نفيدكم أن الموظف المذكور بياناته أعلاه لازال يعمل لدينا حتى تاريخه،
 وقد اعطي هذا الخطاب بناء على طلبه دون ادنى مسؤولية على الشركة.', ['rtl' => true,
    'bold' => true,'size'=> 14], ['align' => 'right']);

$section->addText('', [], ['spacing' => 1000]);
$section->addText($user['sponsor_company'], ['rtl' => true,'size'=> 18], []);
$section->addText('', [], ['spacing' => 150]);
$section->addText(config('letters.signature_name'), ['rtl' => true,'size'=> 18], []);

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


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');

$objWriter->save($temp_file);
header("Content-Disposition: attachment; filename=myFile.docx");
readfile($temp_file); // or echo file_get_contents($temp_file);
unlink($temp_file);