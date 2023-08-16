<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadExcelEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */

	public function __construct(protected  int  $totalCount, 	protected int $processedCount)
	{
	}

	public function broadcastOn()
	{
		return new Channel("upload-excel");
	}

	public function broadcastAs()
	{
		return 'upload.excel';
	}

	/**
	 * @return array
	 */
	public function broadcastWith(): array
	{
		return
		[
			$this->totalCount,
			$this->processedCount,
			'progress' => round(($this->processedCount / $this->totalCount) * 100, 2)
		];
	}


}
