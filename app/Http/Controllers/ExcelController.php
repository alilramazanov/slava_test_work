<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\FileServiceInterface;
use App\Http\Requests\Excel\UploadFileRequest;
use App\Models\Row;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ExcelController extends Controller
{

	public function __construct(
		protected FileServiceInterface $fileService
	)
	{
	}

	/**
	 * @param  UploadFileRequest  $request
	 * @return JsonResponse|void
	 */
    public function store(UploadFileRequest $request){

	    $file = $request->file('file');

		$isUpload = $this->fileService->uploadFile($file);
		if ($isUpload){
			return response()->json(['message' => 'File save completed']);
		}
    }

	/**
	 * @return JsonResponse
	 */
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
