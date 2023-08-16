<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Row
 *
 * @property int $id
 * @property string $name
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\RowFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Row newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Row newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Row query()
 * @method static \Illuminate\Database\Eloquent\Builder|Row whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Row whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Row whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Row whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Row whereUpdatedAt($value)
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Row whereUuid($value)
 * @mixin \Eloquent
 */
class Row extends Model
{
    use HasFactory;
	use HasUuids;

	protected $fillable = [
		'id',
		'name',
		'date'
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$this->primaryKey = 'uuid';
	}

	public function getKeyName()
	{
		return 'uuid';
	}

}
