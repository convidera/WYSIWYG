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
     * @param array                   $displayOnIndex
     *
     * @return ComputedTextElementPanel
     */
    public static function make($name, $fields = [], $wordCount = 7, $displayOnIndex = [])
    {
        if (is_callable($fields)) {
            $fields = $fields();
        }
        if (is_object($fields)) {
            $fields = get_class($fields)::getDefaultTextKeys();
        }

        $computedTextElementFields = [];
        foreach ($fields as $field) {
            $computedTextElement = ComputedTextElement::make($field)->setLength($wordCount);
            if ($displayOnIndex && !in_array($field, $displayOnIndex)) {
                $computedTextElement->hideFromIndex();
            }
            $computedTextElementFields[] = $computedTextElement;
        }

        return new self($name, $computedTextElementFields);
    }
}
