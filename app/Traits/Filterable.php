<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Attribute;

trait Filterable
{

    public function scopeFilter(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {

            $operator = isset($filters["{$key}_operator"]) ?: '';

            if (in_array($key, $this->getRegularAttributes())) {
                $query = $this->applyBasicFilter($query, $key, $value, $operator);
            } else {
                $query = $this->applyEavFilter($query, $key, $value, $operator);
            }
        }

        return $query;
    }

    // This can be overridden on the Model to specify more/less filterable attributes
    protected function getRegularAttributes()
    {
        return $this->fillable;
    }

    protected function applyBasicFilter(Builder $query, $key, $value, $operator)
    {

        switch ($operator) {
            case 'gt':
            case '>':
                return $query->where($key, '>', $value);
            case 'lt':
            case '<':
                return $query->where($key, '<', $value);
            case 'like':
            case 'LIKE':
                return $query->where($key, 'LIKE', "%{$value}%");
            default:
                return $query->where($key, '=', $value);
        }
    }

    protected function applyEavFilter(Builder $query, $attributeName, $value, $operator)
    {

        $attribute = Attribute::where('name', $attributeName)->first();

        if ($attribute) {
            $query = $query->whereHas('attributeValues', function ($query) use ($attribute, $value, $operator) {
                switch ($operator) {
                    case 'gt':
                    case '>':
                        return $query->where('attribute_id', $attribute->id)->where('value', '>', $value);
                    case 'lt':
                    case '<':
                        return $query->where('attribute_id', $attribute->id)->where('value', '<', $value);
                    case 'like':
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
