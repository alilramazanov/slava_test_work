<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileFactory extends Factory
{
	protected $model = File::class;

	public function definition(): array
	{
		return [
			'path' => $this->faker->filePath(),
			'name' => $this->faker->name(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		];
	}
}
