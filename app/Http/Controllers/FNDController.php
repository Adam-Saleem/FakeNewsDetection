<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class FNDController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public string $pythonPath;
    public string $textTestPath;

    public function __construct()
    {
        $this->pythonPath = public_path('python\venv\Scripts\python.exe');
        $this->textTestPath = public_path('python\textTestPath.py');
    }

    public function Text_Test(Request $request)
    {
        $title = $request->get('title');
        $author = $request->get('author');
        $source = $request->get('source');
        $text = $request->get('text');

        $textFile = $this->createTempFile('text', $text);
        $command = $this->pythonPath . ' ' . $this->textTestPath . ' ' . $title. ' ' . $author. ' ' . $source. ' ' . $textFile;
        $result = shell_exec($command);

        return 'test summery title: ' . $result;
    }

    public function Url_Test(Request $request)
    {
        logger($request);
        return 'url';
    }

    public function deleteTempFile($fileName)
    {
        $filePath = public_path('temp') . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function createTempFile($fileName, $content): string
    {
        $fileName = $fileName . '_' . uniqid() . '.txt';
        $filePath = public_path('temp') . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($filePath, $content);
        return $fileName;
    }
}
