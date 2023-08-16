<?php

namespace App\Http\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{

	public function uploadFile(UploadedFile $file): bool;

}