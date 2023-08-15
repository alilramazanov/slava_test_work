<?php

namespace App\Http\Controllers;

use App\Http\Requests\Excel\UploadExcelRequest;
use App\Models\File;

class ExcelController extends Controller
{
    public function store(UploadExcelRequest $request){

	    $file = $request->file('file');
	    $filename = $file->getClientOriginalName();
	    $path = $file->store('public/files');

	    $fileModel = new File();
	    $fileModel->name = $filename;
	    $fileModel->path = $path;
	    $fileModel->save();

	    return response()->json(['message' => 'Файл успешно загружен.'], 200);
    }

	public function show($id){
		$file = File::query()->find($id);

		$filePath = storage_path('app/' . $file->path);

		return response()->download($filePath, $file->name);
	}

	public function parsing($id){


	}
}
