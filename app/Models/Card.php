<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\FilterStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Card
 *
 * @property int $id
 * @property int|null $column_id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $order
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Column|null $column
 * @package App\Models
 * @method static \Database\Factories\CardFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Card hidden()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Query\Builder|Card onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Card withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Card withoutTrashed()
 * @mixin \Eloquent
 */
class Card extends Model
{
	use SoftDeletes, HasFactory;
	protected $table = 'cards';

	protected $casts = [
		'column_id' => 'int'
	];

	protected $fillable = [
		'column_id',
		'title',
		'description',
        'order',
	];

	public function column()
	{
		return $this->belongsTo(Column::class);
	}

    public static function boot(){
        parent::boot();

        static::creating(function ($instance){
            $instance->order = self::query()->max('order') + 1;
        });
    }

    public function scopeFilterStatus($query, $status)
    {
        return $query->when($status == '0', function ($q) {
            $q->onlyTrashed();
        }, function ($q) use ($status) {
            $q->when($status != '1', function ($q) {
                $q->withTrashed();
            });
        });
    }
}
