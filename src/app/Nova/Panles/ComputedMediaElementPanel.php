<?php

namespace Convidera\WYSIWYG\Nova\Panles;

use Convidera\WYSIWYG\Nova\Fields\ComputedMediaElement;
use Convidera\WYSIWYG\UuidModel;

class ComputedMediaElementPanel extends \Laravel\Nova\Panel
{
    /**
     * Create a new panel instance.
     *
     * @param string                  $name
     * @param UuidModel|Closure|array $fields
     *
     * @param int                     $wordCount
     * @param array                   $displayOnindex
     *
     * @return \Laravel\Nova\Panel
     */
    public static function make($name, $fields = [], $displayOnindex = [])
    {
        if (is_array($fields) || is_callable($fields)) {
            return new self($name, $fields);
        }

        $fields = get_class($fields)::getDefaultMediaKeys();
        $computedMediaElementFields = [];
        foreach ($fields as $field) {
            $computedMediaElement = ComputedMediaElement::make($field);
            if ($displayOnindex && !in_array($field, $displayOnindex)) {
                $computedMediaElement->hideFromIndex();
            }
            $computedMediaElementFields[] = $computedMediaElement;
        }

        return new self($name, $computedMediaElementFields);
    }
}
