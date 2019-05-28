<?php

namespace Convidera\WYSIWYG\Nova\Panles;

use Convidera\WYSIWYG\Nova\Fields\ComputedTextElement;
use Convidera\WYSIWYG\UuidModel;

class ComputedTextElementPanel extends \Laravel\Nova\Panel
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
    public static function make($name, $fields = [], $wordCount = 7, $displayOnindex = [])
    {
        if (is_array($fields) || is_callable($fields)) {
            return new self($name, $fields);
        }

        $fields = get_class($fields)::getDefaultTextKeys();
        $computedTextElementFields = [];
        foreach ($fields as $field) {
            $computedTextElement = ComputedTextElement::make($field)->setLength($wordCount);
            if ($displayOnindex && !in_array($field, $displayOnindex)) {
                $computedTextElement->hideFromIndex();
            }
            $computedTextElementFields[] = $computedTextElement;
        }

        return new self($name, $computedTextElementFields);
    }
}
