<?php

namespace App\Http\Services;

use App\Http\Events\UploadExcelEvent;

class BulkService
{
	const STARTED_STATUS = 'started';
	const PROCESSING_STATUS = 'processing';
	const ENDED_STATUS = 'ended';

	/** @inheritDoc */
	public function notify( string $message, string $status, int $totalCount): void
	{
		UploadExcelEvent::dispatch($totalCount,2
		);
	}

}