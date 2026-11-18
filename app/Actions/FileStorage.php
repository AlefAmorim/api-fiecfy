<?php

namespace App\Actions;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorage
{
    private string $baseDisk;
    private string $subDir;

    public function __construct(string $baseDisk, string $subDir)
    {
        $this->baseDisk = $baseDisk ? $baseDisk : 'private';
        $this->subDir = $subDir;
    }

    public function store(File|UploadedFile|string $file, string $fileName) {
        $fs = Storage::disk($this->baseDisk);
        $path = $fs->putFileAs($this->subDir, $file, $fileName);
        $pathUrl = Storage::url($path);
        return $pathUrl;
    }
    public function update(File|UploadedFile|string $file, string $dir) {
        $fs = Storage::disk($this->baseDisk);
        $path = $fs->putFile($dir, $file);
        echo $path;
        // $pathUrl = Storage::url($path);
        return true;
    }
    public function retrieve() {

    }
}
