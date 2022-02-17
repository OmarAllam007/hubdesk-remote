<?php

\PhpOffice\PhpWord\Settings::setCompatibility(false);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
ob_clean();
$objWriter->save($temp_file);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename={$letterTicket->letter->name}-{$user['iqama_number']}.docx");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Content-Length: ' . filesize($temp_file));
readfile($temp_file); // or echo file_get_contents($temp_file);
unlink($temp_file);