<?php

namespace App\Jobs;

use App\Models\Row;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ParseExcelFileJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $filename;
	protected $chunkSize;

	public function __construct($filename)
	{
		$this->chunkSize =  Config::get('optimize-settings.row_split_parts');
		$this->filename = $filename;
		$this->queue = 'parse_excel_file';
	}

	public function handle()
	{
		$spreadsheet = IOFactory::load($this->filename);
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->removeRow(1);

		$rows = [];

		foreach ($sheet->getRowIterator() as $row) {
			$rowData = [];
			foreach ($row->getCellIterator() as $cell) {
				$rowData[] = $cell->getValue();
			}

			$rows[] = $rowData;

			if (count($rows) >= $this->chunkSize) {
				$this->processRows($rows);
				$rows = [];
			}

		}

//		// Обработка последних строк, если остались
		if (count($rows) > 0) {
			$this->processRows($rows);
		}
	}

	private function processRows($rows)
	{
		$data = [];
		foreach ($rows as $row) {
			$data[] = [
				'id' => $row[0],
				'name' => $row[1],
				'date' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
				'created_at' => now(),
			];
		}

		Row::insert($data);
		$this->fail();
	}

}

