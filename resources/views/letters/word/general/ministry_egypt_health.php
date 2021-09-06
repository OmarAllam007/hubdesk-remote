<?php

$phpWord = new \PhpOffice\PhpWord\PhpWord();


$section = $phpWord->addSection(['marginTop' => 2400,
    'marginLeft' => 200, 'marginRight' => 400, 'marginBottom' => 400]);
$section->addText(
    'HD: ' . $letterTicket->ticket->id
);
$section->addText('', [], []);
$section->addText(
    'ID: ' . $letterTicket->ticket->requester->employee_id
);

$section->addText('', [], []);
$section->addText(
    $letterTicket->ticket->requester->business_unit->name
);

$headerStyle = ['bold' => true, "size" => 18, 'underline' => 'single'];
$titleStyle = ["size" => 20, 'rtl' => true];
$rightStyle = ['align' => 'right'];


$section->addText("الموافق : " . $letterTicket->last_approval_date . " م", [], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
//$section->addText('خطاب : تعريف بالراتب', $headerStyle,
//    ['align' => 'center']);
$section->addText('', [], []);
$section->addText("الســادة / {$letterTicket->letter->ar_name}                        المحترمين", $titleStyle, $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText(' ،،، السلام عليكم ورحمة الله وبركاته ،،، وبعد', ['size' => 18], $rightStyle);
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText("نفيدكم نحن {$user['sponsor_company']} بأن الموظف / {$user['ar_name']}، الجنسية / {$user['ar_nationality']}،
 هوية رقم/  {$user['iqama_number']}،", ['size' => 14, 'rtl' => true], $rightStyle);

$section->addText("يعمل لدينا من تاريخ: {$user['date_of_join']} م  بوظيفة: {$user['occupation']}", ['size' => 14, 'rtl' => true], ['align' => 'right', 'rtl' => 'true']);


$section->addText("وقـــد أعطــي هـــذا الخطـاب بنــاءً  علــى طلبـه دون أدنى مسؤوليـة على الشــركة.", ['size' => 14, 'rtl' => true], ['align' => 'right']);
$section->addText('', [], ['spacing' => 1000]);
$section->addText('ولكم وافر الشكر والتقدير ،،،', ['bold' => true, 'size' => 18, 'rtl' => true], ['align' => 'center']);
$section->addText('', [], ['spacing' => 1000]);
$section->addText($user['sponsor_company'], [], []);
$section->addText('', [], ['spacing' => 150]);
//$section->addText(config('letters.signature_name'), [], []);


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');

$objWriter->save($temp_file);
header("Content-Disposition: attachment; filename=myFile.docx");
readfile($temp_file); // or echo file_get_contents($temp_file);
unlink($temp_file);