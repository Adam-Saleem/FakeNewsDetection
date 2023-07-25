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
    public string $textUrlPath;

    public function __construct()
    {
        $this->pythonPath = public_path('python\venv\Scripts\python.exe');
        $this->textTestPath = public_path('python\textTest.py');
        $this->textUrlPath = public_path('python\urlTest.py');
    }

    public function Text_Test(Request $request)
    {
        $title = $request->get('title');
        $author = $request->get('author');
        $source = $request->get('source');
        $text = $request->get('text');

        $textFile = $this->createTempFile('text', $text);
        $command = $this->pythonPath . ' ' .
            escapeshellarg($this->textTestPath) . ' ' .
            escapeshellarg($title) . ' ' .
            escapeshellarg($author) . ' ' .
            escapeshellarg($source) . ' ' .
            escapeshellarg($textFile);

        $result = shell_exec($command);
        $this->deleteTempFile($textFile);
        return json_decode($result, true);
    }

    public function Url_Test(Request $request)
    {
        $url = $request->get('url');
        $command = $this->pythonPath . ' ' . $this->textUrlPath . ' ' . $url;
        $result = shell_exec($command);
        return json_decode($result, true);
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
