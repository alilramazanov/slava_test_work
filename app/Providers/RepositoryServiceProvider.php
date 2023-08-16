<?php

namespace App\Providers;

use App\Http\Interfaces\FileServiceInterface;
use App\Http\Services\ExcelService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(
			FileServiceInterface::class,
			ExcelService::class
		);
	}

}