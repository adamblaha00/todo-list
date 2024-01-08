<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    use HasFactory;
    use ModelTrait;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'datetime',
        'is_done' => 'integer',
    ];

    /**
     * Search scope.
     */
    public static function scopeSearch(Builder $builder, string $search): void
    {
        if (\config('app.env') === 'testing') {
            $builder->getQuery()->where($builder->qualifyColumn('name'), $search);
        } else {
            $builder->getQuery()->whereFullText($builder->qualifyColumn('name'), $search);
        }
    }

    /**
     * Scope past occurrence.
     */
    public static function scopePast(Builder $builder): void
    {
        $builder->getQuery()->whereRaw('CONCAT(date, \' \', time) < NOW()');
    }

    /**
     * Scope upcoming occurrence.
     */
    public static function scopeUpcoming(Builder $builder): void
    {
        $builder->getQuery()->whereRaw('CONCAT(date, \' \', time) >= NOW()');
    }
}
