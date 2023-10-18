<?php

namespace App\Http\Controllers;

use App\Utils\FileStorageUtil;
use App\Utils\ImageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadController extends RestController
{
    public function upload(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'id' => 'required|max:255',
            'path' => 'required|max:255',
            'max_quality' => 'required|numeric|max:100|min:0',
            'min_quality' => 'required|numeric|min:0|max:100',
            'file' => 'required|file'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $id = $request->input('id');
        $path = $request->input('path');
        $max_quality = $request->input('max_quality');
        $min_quality = $request->input('min_quality');

        $file = $request->file('file');
        $type = mime_content_type($file->getRealPath());

        $filename = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();

        $path = FileStorageUtil::joinPaths($id, $path);
        if (Str::startsWith($type, 'image/')) {
            try {
                $url = ImageUtil::save(ImageUtil::getBase64String($file->getRealPath(), $type), $filename, $path, $max_quality, $min_quality);
            } catch (\Exception $e) {
                Log::error($e);
                $url = FileStorageUtil::getInstance()->putFile($path, $file);
            }
        } else {
            $url = FileStorageUtil::getInstance()->putFile($path, $file);
        }

        if ($url) {
            return $this->success(['url' => $url]);
        }
        return $this->error('Cannot save file');
    }

    public function info(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'id' => 'required|max:255',
            'url' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $url = $request->input('url');
        $info = [

        ];
        if (Str::startsWith($url, FileStorageUtil::getInstance()->getRootUrl())) {
            $path = FileStorageUtil::getInstance()->getPath($url);
            $info = FileStorageUtil::getInstance()->getInfoAttributes($path);
        }
        return $this->success($info);
    }

    public function delete(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'id' => 'required|max:255',
            'url' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $url = $request->input('url');
        if (FileStorageUtil::getInstance()->deleteFiles($url)) {
            return $this->success([]);
        }
        return $this->error('Cannot delete');
    }
}
