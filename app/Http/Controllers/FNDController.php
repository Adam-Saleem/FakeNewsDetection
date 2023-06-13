<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class FNDController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function Text_Test(Request $request)
    {
        $title = $request->get('title');
        $author =$request->get('author');
        $source = $request->get('source');
        $text = $request->get('text');


        $pythonPath = public_path('python\venv\Scripts\python.exe');
        $textRankOneTextPath = public_path('python\textRankOneText.py');
        $normalizationPath = public_path('python\normalization.py');
        $baysianPath = public_path('python\dynamicBaysian_Vectors.py');


        $normalize_file_path = public_path('python\textNeedNormalize.txt');

        file_put_contents($normalize_file_path, $title);
        $normalizeTitleCommand = $pythonPath . ' ' . $normalizationPath . ' 2>&1';
        $normalizeTitle = shell_exec($normalizeTitleCommand);

        $text_file_path = public_path('python\text.txt');

        file_put_contents($text_file_path,$text);
        $textToSummary = $pythonPath . ' ' . $textRankOneTextPath . ' 2>&1';
        $summary = shell_exec($textToSummary);

        file_put_contents($normalize_file_path, $summary);
        $summaryToNormalizeText = $pythonPath . ' ' . $normalizationPath . ' 2>&1';
        $normalizeText = shell_exec($summaryToNormalizeText);


        $testData_file_path = public_path('python\testData.csv');
        file_put_contents($testData_file_path,"title,summary,authors,source");
        $file = file_get_contents($testData_file_path);

        $normalizeTitle = str_replace(",","",$normalizeTitle);
        $normalizeText = str_replace(",","",$normalizeText);
        $author = str_replace(",","",$author);
        $source = str_replace(",","",$source);
        
        $data = "$file\n"."$normalizeTitle,$normalizeText,$author,$source";
        logger($data);
        fwrite(fopen($testData_file_path, 'w'), $data);

        $baysianRun = $pythonPath . ' ' . $baysianPath . ' 2>&1';
        $result = shell_exec($baysianRun);
        logger($result);

        return redirect('/');
    }

    public function Url_Test(Request $request)
    {
        logger($request);
        return redirect('/');
    }
}
