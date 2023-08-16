<?php

namespace App\Observers;

use App\Jobs\ParseExcelFileJob;
use App\Models\File;
use Illuminate\Support\Facades\Cache;

class FileObserver
{
	/**
	 * Handle the File "created" event.
	 */
	public function created(File $file): void
	{
		Cache::forget(ParseExcelFileJob::CACHE_KEY);

		$filePath = storage_path('app/' . $file->path);

		ParseExcelFileJob::dispatch($filePath);
	}

	/**
	 * Handle the File "updated" event.
	 */
	public function updated(File $file): void
	{
		//
	}

	/**
	 * Handle the File "deleted" event.
	 */
	public function deleted(File $file): void
	{
		//
	}

	/**
	 * Handle the File "restored" event.
	 */
	public function restored(File $file): void
	{
		//
	}

	/**
	 * Handle the File "force deleted" event.
	 */
	public function forceDeleted(File $file): void
	{
		//
	}
}
