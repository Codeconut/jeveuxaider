<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersYoungLieu implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query
            ->where(function ($query) use ($value) {
                $query
                    ->where('city', 'LIKE', '%' . $value . '%')
                    ->orWhere('zip', 'LIKE', '%' . $value . '%')
                ;
            })
        ;
    }
}
