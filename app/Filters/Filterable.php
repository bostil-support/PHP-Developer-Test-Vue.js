<?php

namespace App\Filters;

trait Filterable
{

    /**
     * Scope filter for model
     *
     * @param $builder
     * @param  null  $request
     *
     * @return mixed
     */
    public function scopeFilter($builder, $request = null)
    {
        // Get the current model name with namespace
        $model = get_class($this);

        // Make a filter name with namespace
        $filter = 'App\\Filters\\' . class_basename($model) . 'Filter';

        // If filter class exists
        if (class_exists($filter)) {
            // Apply filters from the current query string
            return (new $filter($request ?? request(), $builder, $this))->apply();
        }

        return $builder;
    }
}
