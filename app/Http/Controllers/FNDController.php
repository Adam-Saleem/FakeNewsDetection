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

        $normalizeTitle = str_replace(array("\r", "\n",",")," ",$normalizeTitle);
        $normalizeText = str_replace(array("\r", "\n",",")," ",$normalizeText);
        $author = str_replace(array("\r", "\n",",")," ",$author);
        $source = str_replace(array("\r", "\n",",")," ",$source);

        $testData_file_path = public_path('python\testData.csv');
        $data = "title,summary,authors,source\n"."$normalizeTitle,$normalizeText,$author,$source";
        file_put_contents($testData_file_path,$data);

//        $baysianRun = $pythonPath . ' ' . $baysianPath . ' 2>&1';
//        $result = shell_exec($baysianRun);
//        logger($result);

        return redirect('/');
    }

    public function Url_Test(Request $request)
    {
        logger($request);
        return redirect('/');
    }
}
