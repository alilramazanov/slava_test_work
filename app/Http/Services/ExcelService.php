<?php

namespace App\Http\Services;

use App\Exceptions\ApiErrorException;
use App\Http\Interfaces\FileServiceInterface;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Mockery\Exception;

class ExcelService implements FileServiceInterface
{
	/**
	 * @param  UploadedFile  $file
	 * @return bool
	 * @throws ApiErrorException
	 */
	public function uploadFile(UploadedFile $file): bool
	{
		try {
			$filename = $file->getClientOriginalName();
			$path = $file->store('public/files');

			$file = new File();
			$file->name = $filename;
			$file->path = $path;
			$file->save();

			return true;
		} catch  (Exception $e){
			throw new ApiErrorException($e->getMessage());
		}
	}
}