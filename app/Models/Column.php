<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\FilterStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Column
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Card[] $cards
 * @package App\Models
 * @property-read int|null $cards_count
 * @method static \Database\Factories\ColumnFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Column hidden()
 * @method static \Illuminate\Database\Eloquent\Builder|Column newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Column newQuery()
 * @method static \Illuminate\Database\Query\Builder|Column onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Column query()
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Column whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Column withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Column withoutTrashed()
 * @mixin \Eloquent
 */
class Column extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'columns';

	protected $fillable = [
		'title'
	];

	public function cards()
	{
		return $this->hasMany(Card::class)->orderBy('order');
	}

    public static function getColumnsWithCards($request)
    {
        return self::query()->withWhereHas('cards', function ($q) use ($request) {
            $q->filterStatus($request->status);
        })->when($request->date('date'), function ($q) use ($request) {
            $q->whereBetween('created_at', [Carbon::parse($request->date('date'))->startOfDay(), Carbon::parse($request->date('date'))->endOfDay()]);
        })->get()->keyBy('id');
    }
}
