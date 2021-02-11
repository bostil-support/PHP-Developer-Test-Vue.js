<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class AbstractFilter
{

    protected $request;
    protected $builder;
    protected $model;

    public function __construct(Request $request, $builder, Model $model)
    {
        $this->request = $request;
        $this->builder   = $builder;
        $this->model = $model;
    }

    /**
     * Get all filters from the query
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->request->query();
    }

    /**
     * Apply filters to the query
     *
     * @param $query
     *
     * @return mixed
     */
    public function apply()
    {
        foreach ($this->getFilters() as $filter_name => $value) {
            $method_name = Str::camel($filter_name);

            // Call filter if exists by name
            if (method_exists($this, $method_name)) {
                $this->$method_name($value);
            }
        }

        return $this->builder;
    }

}