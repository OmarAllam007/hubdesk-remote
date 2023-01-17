<?php
dd('asda');

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$sponsorEnName = str_replace("&"," And ",\App\Helpers\LetterSponserMap::$en_sponsers[$user['sponsor_id']]);
$reason = $letterTicket->ticket->fields->last() ? $letterTicket->ticket->fields->last()->value : '';

$section = $phpWord->addSection(['marginTop' => 2400,
    'marginLeft' => 200, 'marginRight' => 400, 'marginBottom' => 400]);

$section->addText(
    'ID: ' . $letterTicket->ticket->requester->employee_id
);
$section->addText('', [], []);

$section->addText(
    'HD: ' . $letterTicket->ticket->id
);


$section->addText('', [], []);
$section->addText(
    str_replace("&"," And ",$letterTicket->ticket->requester->business_unit->name)
);
$section->addText('', [], []);
$section->addText("Date : " . $letterTicket->last_approval_date . "", []);



$section->addText('', [], []);
$section->addText('', [], []);
//$section->addText('خطاب : تعريف بالراتب', $headerStyle,
//    ['align' => 'center']);
$section->addText('', [], []);
$section->addText("His Excellency");
$section->addText('', [], []);
$section->addText("The Consul General");
$section->addText('', [], []);

$section->addText("To The ". str_replace("&"," And ",$letterTicket->ticket->fields()->first()->value)." Embassy");
$section->addText('', [], []);
$section->addText('', [], []);
$section->addText("Dear Sir,");


$section->addText("This is to certify that Mr. {$user['en_name']} {$user['en_nationality']}, ID Number {$user['iqama_number']}, Passport Number {$user['passport_number']}. Is an employee of {$sponsorEnName}, from {$user['date_of_join']} up to the present.");

$section->addText('', [], []);

$section->addText("He is currently working as an {$user['en_occupation']}, and receiving a Total salary of ({$user['total_package']} SR) Monthly.");
$section->addText('', [], []);
$section->addText("We hereby certify that the above-mentioned will be visiting your country. This certification is issued upon the request of the a fore-mentioned party. Without any liability to the company");


$section->addText('', [], ['spacing' => 1000]);
$section->addText($sponsorEnName, [], []);
$section->addText('', [], ['spacing' => 150]);
//$section->addText(config('letters.signature_name'), [], []);


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');

$objWriter->save($temp_file);
header("Content-Disposition: attachment; filename=myFile.docx");
readfile($temp_file); // or echo file_get_contents($temp_file);
unlink($temp_file);