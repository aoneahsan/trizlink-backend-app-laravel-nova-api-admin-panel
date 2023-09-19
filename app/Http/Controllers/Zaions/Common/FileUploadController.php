<?php

namespace App\Http\Controllers\Zaions\Common;

use App\Http\Controllers\Controller;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function uploadSingleFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $fileName = 'NO File Found';
        $filePath = '-';
        $fileUrl = '-';
        if ($request->file('file')) {
            $fileData = ZHelpers::storeFile($request, 'file', 'uploaded-files');
            if ($fileData) {
                $filePath = $fileData['filePath'];
                $fileUrl = $fileData['fileUrl'];
            }
            $fileName = $request->file('file');
        }

        return response()->json(['data' => ['fileName' => $fileName, 'filePath' => $filePath, 'fileUrl' => $fileUrl, 'file' => $request->file]]);
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'files.*' => 'file|max:2000'
        ]);

        $filesData = [];
        // $filesData = ZHelpers::storeFiles($request->files, 'uploaded-files');
        // $filesData = ZHelpers::storeFiles($request->files, 'uploaded-files');
        if ($request->files) {
            foreach ($request->files as $files) {
                foreach ($files as $file) {
                    $filesData[] = [
                        'name' => $file[0]->getClientOriginalName(),
                        'ch' => 0,
                        'count1' => $request->get('files') ? count($request->get('files')) : null,
                        'count2' => $request->files ? count($request->files) : null,
                        // 'count3' => $file ? count($file) : null
                    ];
                }
            }
        }

        return response()->json(['data' => [
            'filesData' => $filesData
        ]]);
    }

    public function getSingleFileUrl(Request $request)
    {
        $request->validate([
            'filePath' => 'string'
        ]);
        $fileUrl = ZHelpers::getFullFileUrl($request->filePath);

        return response()->json(['data' => ['fileUrl' => $fileUrl]]);
    }

    public function checkIfSingleFileExists(Request $request)
    {

        $request->validate([
            'filePath' => 'string'
        ]);
        $fileExists = ZHelpers::checkIfFileExists($request->filePath);

        return response()->json(['data' => [
            'fileExists' => $fileExists
        ]]);
    }

    public function deleteSingleFile(Request $request)
    {
        $request->validate([
            'filePath' => 'string'
        ]);
        $deleted = ZHelpers::deleteFile($request->filePath);

        return response()->json(['data' => [
            'deleted' => $deleted
        ]]);
    }
}
