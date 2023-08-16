<?php

namespace App\Http\Controllers;

use App\Http\Requests\Excel\UploadExcelRequest;
use App\Jobs\ParseExcelFileJob;
use App\Models\File;
use App\Models\Row;
use DB;
use Illuminate\Support\Facades\Cache;

class ExcelController extends Controller
{
    public function store(UploadExcelRequest $request){

	    $file = $request->file('file');
	    $filename = $file->getClientOriginalName();
	    $path = $file->store('public/files');

	    $file = new File();
	    $file->name = $filename;
	    $file->path = $path;
	    $file->save();

	    $filePath = storage_path('app/' . $file->path);

		ParseExcelFileJob::dispatch($filePath);

	    return response()->json(['message' => 'Файл успешно загружен.']);
    }

	public function index(){

		$rows = Row::query()
			->select(['date', DB::raw('json_agg(name) as names'), DB::raw('json_agg(id) as ids')])
			->groupBy('date')
			->orderBy('date')
			->get()
			->mapWithKeys(function ($row) {
				return [
					$row->date => [
						'ids' => json_decode($row->ids),
						'names' => json_decode($row->names)
					]
				];
			});

		return response()->json($rows);

	}
}
