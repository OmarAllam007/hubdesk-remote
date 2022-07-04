<?php
//
$to = $letterTicket->ticket->fields->where('name','Region')->first() ? $letterTicket->ticket->fields->where('name','Region')->first()->value : '';
$IstiqdamTo = $letterTicket->ticket->fields->where('name','Request For')->first() ? $letterTicket->ticket->fields->where('name','Request For')->first()->value : '';

$regions = $letterTicket->letter->fields->where('name','like','Region')->first();
$options = $regions['options'];
$decodeString = json_decode($options);
$regions_data = array_combine($decodeString, $decodeString);

$region_ar = \App\Translation::where('word','like',$regions_data[$to])
    ->where('language','ar')->first();

$to_ar = \App\Translation::where('word','like',$IstiqdamTo)
    ->where('language','ar')->first();

$education = $letterTicket->ticket->fields->where('name','Academic Qualification')->first() ?? '';
$specialization = $letterTicket->ticket->fields->where('name','Specialization')->first() ?? '';

$phpWord = new \PhpOffice\PhpWord\PhpWord();


$titleStyle = ["size" => 14, 'rtl' => true];
$rightStyle = ['align' => 'right'];

$section = $phpWord->addSection(['marginTop' => 2400,
    'marginLeft' => 200, 'marginRight' => 400, 'marginBottom' => 400]);
$section->addText(
    'HD: ' . $letterTicket->ticket->id
);
$section->addText('', [], []);
$section->addText(
    'ID: ' . $letterTicket->ticket->requester->employee_id
);
//
$section->addText('', [], []);
$section->addText(
    str_replace('&', 'and', $letterTicket->ticket->requester->business_unit->name));

//$titleStyle = ["size" => 14, 'rtl' => true];
//$rightStyle = ['align' => 'right'];
//
$section->addText('', [], []);
$section->addText('', [], []);


$section->addText('', [], []);

$translation = $region_ar->translation ?? "";
$section->addText("شئون الإستقدام بـ / {$translation}       المحترمين", ['size' => 14], $rightStyle);

$section->addText('', [], []);
$section->addText('', [], []);
$section->addText(' ،،، السلام عليكم ورحمة الله وبركاته ،،، وبعد', ['size' => 14], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText("نود إفادة سعادتكم ان موظفنا المدعو / {$user['ar_name']} ، الجنسية / {$user['ar_nationality']} والذي يعمل لدينا بمهنة / {$user['occupation']}", ['size' => 14, 'rtl' => true], $rightStyle);
$section->addText('', [], []);
$section->addText(" حسب المهنة الموضحة بالاقامة رقم/ {$user['iqama_number']} ، والذي يتقاضى راتب شهريا ( {$user['total_package']} ريال ) ولديه مؤهل {$education->value}  تخصص {$specialization->value} .", ['size' => 14, 'rtl' => true], $rightStyle);

$istiqdamTranslation = $to_ar->translation ?? "";
$section->addText("يرغب في استقدام / {$istiqdamTranslation} وحيث لا مانع لدينا من ذلك حيث ان العلاج والسكن مؤمن للمذكور ، عليه نأمل الموافقة على طلب موظفنا كما نصادق على صحة المعلومات الموضحة بخطابنا هذا ، وهذا إقرار منا بذلك .", ['size' => 14, 'rtl' => true], ['align' => 'right']);

$section->addText('', [], ['spacing' => 1000]);
$section->addText('ولكم جزيل الشكر ،،،', ['bold' => true, 'size' => 14, 'rtl' => true], ['align' => 'center']);
$section->addText('', [], ['spacing' => 1000]);
$section->addText($user['sponsor_company'], [], []);
$section->addText('', [], ['spacing' => 150]);
$section->addText(config('letters.signature_name'), [], []);
///
\PhpOffice\PhpWord\Settings::setCompatibility(false);


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
ob_clean();
$objWriter->save($temp_file);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=issueVisa{$user['iqama_number']}.docx");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Content-Length: ' . filesize($temp_file));
readfile($temp_file); // or echo file_get_contents($temp_file);
unlink($temp_file);