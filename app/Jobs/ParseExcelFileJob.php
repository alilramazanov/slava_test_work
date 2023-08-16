<?php

namespace App\Jobs;

use App\Http\Events\UploadExcelEvent;
use App\Http\Services\BulkService;
use App\Models\Row;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ParseExcelFileJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected string $filename;
	protected int $chunkSize;
	protected int $totalRows;
	protected BulkService $bulkService;

	public const CACHE_KEY = 'userId:' . 1;
	protected const FIRST_COLUMN = 1;

	public function __construct($filename)
	{
		$this->filename = $filename;
		$this->totalRows = IOFactory::load($this->filename)->getActiveSheet()->getHighestRow();
		$this->chunkSize =  Config::get('optimize-settings.row_split_parts');
		$this->queue = 'parse_excel_file';
		$this->bulkService = resolve(BulkService::class);
	}

	/**
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 *
	 */
	public function handle()
	{
		$spreadsheet = IOFactory::load($this->filename);
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->removeRow(self::FIRST_COLUMN);

		foreach ($sheet->getRowIterator() as $row) {
			$rowData = [];
			foreach ($row->getCellIterator() as $cell) {
				$rowData[] = $cell->getCalculatedValue();
			}

			$rows[] = $rowData;

			if (count($rows) >= $this->chunkSize) {
				$this->processRows($rows);
				$rows = [];
			}
			$this->sendProgressMessage();
		}

		if (count($rows) > 0) {
			$this->processRows($rows);
		}
	}

	private function processRows($rows)
	{
		$data = [];
		foreach ($rows as $row) {
			$data[] = [
				'uuid' => Str::uuid(),
				'id' => $row[0],
				'name' => $row[1],
				'date' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
				'created_at' => now(),
			];
		}
		Row::insert($data);

		$processedRows = Cache::get(self::CACHE_KEY);
		UploadExcelEvent::dispatch($this->totalRows, $processedRows);

	}

	public function sendProgressMessage(){
		$oldValue = Cache::get(self::CACHE_KEY);
		$processedRows = $oldValue + 1;
		Cache::put(self::CACHE_KEY, $processedRows);

	}
}

