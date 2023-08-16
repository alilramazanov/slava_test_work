<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BulkResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			"message"     => $this->message,
			"status"      => $this->status,
			"total_count" => $this->totalCount,
		];
	}
}
