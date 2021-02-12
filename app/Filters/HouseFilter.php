<?php


namespace App\Filters;


class HouseFilter extends AbstractFilter
{
    public function orderBy($value)
    {
        $sort = $this->request->query('sort');

        $direction = in_array($sort, ['desc', 'descending'])
            ? 'DESC'
            : 'ASC';

        if ($value) {
            return $this->builder->orderBy('name', $direction);
        }

        return $this->builder;
    }

    public function name($value)
    {
        if (strlen($value) > 0) {
            return $this->builder->where('name', 'like', "%{$value}%");
        }

        return $this->builder;
    }

    public function bedrooms($value)
    {
        if (is_array($value) && count($value)) {
            return $this->builder->whereIn('bedrooms', $value);
        }

        return $this->builder;
    }

    public function bathrooms($value)
    {
        if (is_array($value) && count($value)) {
            return $this->builder->whereIn('bathrooms', $value);
        }

        return $this->builder;
    }

    public function storeys($value)
    {
        if (is_array($value) && count($value)) {
            return $this->builder->whereIn('storeys', $value);
        }

        return $this->builder;
    }

    public function garages($value)
    {
        if (is_array($value) && count($value)) {
            return $this->builder->whereIn('garages', $value);
        }

        return $this->builder;
    }

    public function priceFrom($value)
    {
        $value = (int) $value;

        if ($value > 0) {
            return $this->builder->where('price', '>=', $value);
        }

        return $this->builder;
    }

    public function priceTo($value)
    {
        $value = (int) $value;

        if ($value > 0) {
            return $this->builder->where('price', '<=', $value);
        }

        return $this->builder;
    }
}
