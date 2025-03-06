<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Attribute;

trait Filterable
{
    /**
     * Apply filters to a model query, including EAV (Entity-Attribute-Value) and regular attributes.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            // Handle regular (non-EAV) filters like name, status, etc.
            if (in_array($key, $this->getRegularAttributes())) {
                $query = $this->applyBasicFilter($query, $key, $value);
            }
            // Handle EAV filters (dynamic attributes)
            else {
                $query = $this->applyEavFilter($query, $key, $value);
            }
        }

        return $query;
    }

    /**
     * Get a list of regular attributes that can be filtered.
     *
     * @return array
     */
    protected function getRegularAttributes()
    {
        return ['name', 'status'];  // List regular attributes for each model here.
    }

    /**
     * Apply basic filters to regular attributes (like name, status, etc.).
     *
     * @param Builder $query
     * @param string $key
     * @param mixed $value
     * @return Builder
     */
    protected function applyBasicFilter(Builder $query, $key, $value)
    {
        $operator = request()->get("filters.{$key}_operator", '=');

        switch ($operator) {
            case '>':
                return $query->where($key, '>', $value);
            case '<':
                return $query->where($key, '<', $value);
            case 'LIKE':
                return $query->where($key, 'LIKE', "%{$value}%");
            default:
                return $query->where($key, '=', $value);
        }
    }

    /**
     * Apply filters to EAV (Entity-Attribute-Value) attributes.
     *
     * @param Builder $query
     * @param string $attributeName
     * @param mixed $value
     * @return Builder
     */
    protected function applyEavFilter(Builder $query, $attributeName, $value)
    {
        $operator = request()->get("filters.{$attributeName}_operator", '=');

        // Find the attribute by name
        $attribute = Attribute::where('name', $attributeName)->first();

        if ($attribute) {
            $query = $query->whereHas('attributeValues', function ($query) use ($attribute, $value, $operator) {
                switch ($operator) {
                    case '>':
                        return $query->where('attribute_id', $attribute->id)->where('value', '>', $value);
                    case '<':
                        return $query->where('attribute_id', $attribute->id)->where('value', '<', $value);
                    case 'LIKE':
                        return $query->where('attribute_id', $attribute->id)->where('value', 'LIKE', "%{$value}%");
                    default:
                        return $query->where('attribute_id', $attribute->id)->where('value', '=', $value);
                }
            });
        }

        return $query;
    }
}
