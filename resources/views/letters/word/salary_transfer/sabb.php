<?php
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$fileName = 'Event_calendar' . '-' . now()->toDateString() . '.doc';

$section = $phpWord->addSection();
$render = str_replace('<!doctype html>', '', $content->render());
//dd(strip_tags($render));

//dd($render);
//\PhpOffice\PhpWord\Shared\Html::addHtml($section, strip_tags($render), true, false);
////$text = $section->addPreserveText($render);
////header('Content-Type: application/octet-stream');
////header("Cache-Control: no-cache, must-revalidate");
////header("Pragma: no-cache");
////header("Content-Disposition: attachment; filename=$fileName");
////ob_clean();
////readfile($fileName);
//header("Content-Type: application/vnd.ms-word");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("content-disposition: attachment;filename=Report.doc");
//require resource_path() . '/views/letters/word/_footer.php';