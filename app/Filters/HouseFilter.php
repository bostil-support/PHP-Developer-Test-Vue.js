<?php


namespace App\Filters;


class HouseFilter extends AbstractFilter
{
    public function orderBy($value)
    {
        $direction = $this->request->query('sort') === 'desc' ? 'DESC' : 'ASC';

        if ($value) {
            return $this->builder->orderBy('name', $direction);
        }

        return $this->builder;
    }

    public function name($value)
    {
        if (strlen($value) > 0) {
            return $this->builder->where('name', 'like', "{$value}%");
        }

        return $this->builder;
    }

    public function bedrooms($value)
    {
        $value = (int) $value;

        if ($value > 0) {
            return $this->builder->where('bedrooms', '=', $value);
        }

        return $this->builder;
    }

    public function bathrooms($value)
    {
        $value = (int) $value;

        if ($value > 0) {
            return $this->builder->where('bathrooms', '=', $value);
        }

        return $this->builder;
    }

    public function storeys($value)
    {
        $value = (int) $value;

        if ($value > 0) {
            return $this->builder->where('storeys', '=', $value);
        }

        return $this->builder;
    }

    public function garages($value)
    {
        $value = (int) $value;

        if ($value > 0) {
            return $this->builder->where('garages', '=', $value);
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
