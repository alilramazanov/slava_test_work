<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\FileServiceInterface;
use App\Http\Requests\Excel\UploadExcelRequest;
use App\Models\Row;
use DB;

class ExcelController extends Controller
{

	public function __construct(
		protected FileServiceInterface $fileService
	)
	{
	}

	/**
	 * @param  UploadExcelRequest  $request
	 * @return \Illuminate\Http\JsonResponse|void
	 */
    public function store(UploadExcelRequest $request){

	    $file = $request->file('file');

		$isUpload = $this->fileService->uploadFile($file);
		if ($isUpload){
			return response()->json(['message' => 'File save completed']);
		}
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
