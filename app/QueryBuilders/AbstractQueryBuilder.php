<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

abstract class AbstractQueryBuilder
{
    public const INVALID_FILTER_MESSAGE = 'Invalid filter %s given, removing.';
    public const INVALID_SORT_MESSAGE = 'Invalid sort %s given, removing.';

    public const FILTER_IN_KEY = 'in';
    public const FILTER_EQUALS_KEY = 'eq';
    public const FILTER_GREATER_THAN_KEY = 'gt';
    public const FILTER_LESS_THAN_KEY = 'lt';
    public const FILTER_GREATER_THAN_OR_EQUAL_KEY = 'gte';
    public const FILTER_LESS_THAN_OR_EQUAL_KEY = 'lte';

    public const SORT_ASCENDING_KEY = 'asc';
    public const SORT_DESCENDING_KEY = 'desc';

    abstract public function build(array $filters = [], array $sorts = [], string $order = null): Builder;

    protected function sanitiseAndApplyFilters(Builder $query, array $filters): void
    {
        if (empty($filters)) {
            return;
        }

        $model = $query->getModel();
        if (!is_a($model, Model::class)) {
            Log::error("Query builder - model is an instance of query builder");
            return;
        }

        $associationFilters = Arr::only($filters, ['designated_seats', 'berths']);

        $this->sanitiseModelFilters($model, $filters);

        $this->applyModelFilters($query, $filters);

        $this->applyAssociationFilters($query, $associationFilters);
    }

    protected function sanitiseAndApplySorts(Builder $query, array $sorts): void
    {
        if (empty($sorts)) {
            return;
        }

        $model = $query->getModel();
        if (!is_a($model, Model::class)) {
            Log::error("Query builder - model is an instance of query builder");
            return;
        }

        $this->sanitiseSorts($model, $sorts);

        $this->applySorts($query, $sorts);
    }

    private function sanitiseModelFilters(Model $model, array &$filters): void
    {
        $table = $model->getTable();

        $columns = Schema::getColumnListing($table);

        $filterNames = array_keys($filters);

        $validFilters = array_intersect($filterNames, $columns);

        $filters = array_filter($filters, function ($filter) use ($validFilters) {
            return in_array($filter, $validFilters);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function applyModelFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $property => $operatorData) {
            $property = (string) $property;
            $this->applyFilter($query, $property, $operatorData);
        }
    }

    private function applyAssociationFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $property => $operatorData) {
            $property = (string) $property;
            switch ($property) {
                case 'berths':
                    $query->whereHas('berths', function ($berthQuery) use ($operatorData) {
                        $this->applyFilter($berthQuery, 'number', $operatorData);
                    });
                    break;
                case 'designated_seats':
                    $query->whereHas('seats', function ($berthQuery) use ($operatorData) {
                        $this->applyFilter($berthQuery, 'number', $operatorData);
                    });
                    break;
                default:
                    break;
            }
        }
    }

    private function applyFilter(Builder $query, string $property, array $operatorData): void
    {
        if ($this->hasEqualsOperator($operatorData)) {
            $this->applyEqualsQuery($query, $property, $operatorData[self::FILTER_EQUALS_KEY]);
        }

        if ($this->hasGreaterThanOperator($operatorData)) {
            $this->applyGreaterThanQuery($query, $property, $operatorData[self::FILTER_GREATER_THAN_KEY]);
        }

        if ($this->hasLessThanOperator($operatorData)) {
            $this->applyLessThanQuery($query, $property, $operatorData[self::FILTER_LESS_THAN_KEY]);
        }

        if ($this->hasGreaterThanOrEqualToOperator($operatorData)) {
            $this->applyGreaterThanOrEqualToQuery(
                $query,
                $property,
                $operatorData[self::FILTER_GREATER_THAN_OR_EQUAL_KEY]
            );
        }

        if ($this->hasLessThanOrEqualToOperator($operatorData)) {
            $this->applyLessThanOrEqualToQuery(
                $query,
                $property,
                $operatorData[self::FILTER_LESS_THAN_OR_EQUAL_KEY]
            );
        }

        if ($this->hasInOperator($operatorData)) {
            $this->applyInQuery($query, $property, $operatorData[self::FILTER_IN_KEY]);
        }
    }

    private function sanitiseSorts(Model $model, array &$sorts): void
    {
        $table = $model->getTable();

        $columns = Schema::getColumnListing($table);

        $sortNames = array_keys($sorts);

        $validSorts = array_intersect($sortNames, $columns);

        $sorts = array_filter($sorts, function ($sort) use ($validSorts) {
            return in_array($sort, $validSorts);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function applySorts(Builder $query, array $sorts): void
    {
        foreach ($sorts as $property => $direction) {
            $property = (string) $property;
            if (self::SORT_ASCENDING_KEY === $direction) {
                $this->applyAscendingSort($query, $property);
            }

            if (self::SORT_DESCENDING_KEY === $direction) {
                $this->applyDescendingSort($query, $property);
            }
        }
    }

    private function hasEqualsOperator(array $operatorData): bool
    {
        return in_array(self::FILTER_EQUALS_KEY, array_keys($operatorData));
    }

    private function hasGreaterThanOperator(array $operatorData): bool
    {
        return in_array(self::FILTER_GREATER_THAN_KEY, array_keys($operatorData));
    }

    private function hasLessThanOperator(array $operatorData): bool
    {
        return in_array(self::FILTER_LESS_THAN_KEY, array_keys($operatorData));
    }

    private function hasGreaterThanOrEqualToOperator(array $operatorData): bool
    {
        return in_array(self::FILTER_GREATER_THAN_OR_EQUAL_KEY, array_keys($operatorData));
    }

    private function hasLessThanOrEqualToOperator(array $operatorData): bool
    {
        return in_array(self::FILTER_LESS_THAN_OR_EQUAL_KEY, array_keys($operatorData));
    }

    private function hasInOperator(array $operatorData): bool
    {
        return in_array(self::FILTER_IN_KEY, array_keys($operatorData));
    }

    /**
     * @param mixed $value
     */
    private function applyEqualsQuery(Builder $query, string $property, $value): void
    {
        $query->where($property, '=', $value);
    }

    /**
     * @param mixed $value
     */
    private function applyGreaterThanQuery(Builder $query, string $property, $value): void
    {
        $query->where($property, '>', $value);
    }

    /**
     * @param mixed $value
     */
    private function applyLessThanQuery(Builder $query, string $property, $value): void
    {
        $query->where($property, '<', $value);
    }

    /**
     * @param mixed $value
     */
    private function applyGreaterThanOrEqualToQuery(Builder $query, string $property, $value): void
    {
        $query->where($property, '>=', $value);
    }

    /**
     * @param mixed $value
     */
    private function applyLessThanOrEqualToQuery(Builder $query, string $property, $value): void
    {
        $query->where($property, '<=', $value);
    }

    /**
     * @param mixed $value
     */
    private function applyInQuery(Builder $query, string $property, $value): void
    {
        $query->whereIn($property, $value);
    }

    private function applyAscendingSort(Builder $query, string $property): void
    {
        $query->orderBy($property, self::SORT_ASCENDING_KEY);
    }

    private function applyDescendingSort(Builder $query, string $property): void
    {
        $query->orderBy($property, self::SORT_DESCENDING_KEY);
    }
}
