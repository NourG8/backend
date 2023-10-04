<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Exception;
use \PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
class wordTestController extends Controller
{
    public function createWordDocx(){
        $wordTest = new PhpWord();
        $newSection_1 = $wordTest->addSection();
        $docs1 = "Hello ";
        $newSection_1->addText($docs1);

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, 'Word2007');
            try {
                $objWriter->save(storage_path('helloWorld.docx'));
            } catch (Exception $e) {
            }
            return response()->download(storage_path('helloWorld.docx'));
    }

    public function readWordDocx(){
        $template = "contract\Contrat_CDI.docx";
        $templateProcessor = new TemplateProcessor($template);
        $templateProcessor->setValue('firstName', 'Maraym');
        $templateProcessor->setValue('lastName', 'Mokded');
        $templateProcessor->saveAs('contract\helloWorlds.docx');

        $new_file_name = "contract\Contrat_CDI.docx";
        $saveDocPath = public_path($new_file_name);
        $saveImagePath = public_path("image.jpg");

        // Add watermark
        $tmpFile = uniqid('TMP_');
        $PHPWord = \PhpOffice\PhpWord\IOFactory::load($saveDocPath);
        $section = $PHPWord->addSection();
        $header = $section->addHeader();
        $text =" testttttttttt";
        $section->addWatermark($text,array('text'));
        $PHPWord->save("test.docx");
    }

}
