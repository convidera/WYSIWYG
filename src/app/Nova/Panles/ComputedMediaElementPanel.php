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
     * @param array                   $displayOnIndex
     *
     * @param string|null             $path
     * @param bool                    $usesClientOriginalName
     *
     * @return ComputedMediaElementPanel
     */
    public static function make($name, $fields = [], $displayOnIndex = [], string $path = null, bool $usesClientOriginalName = false)
    {
        if (is_array($fields) || is_callable($fields)) {
            return new self($name, $fields);
        }

        $fields = get_class($fields)::getDefaultMediaKeys();
        $computedMediaElementFields = [];
        foreach ($fields as $field) {
            $computedMediaElement = ComputedMediaElement::make($field);
            if ($displayOnIndex && !in_array($field, $displayOnIndex)) {
                $computedMediaElement->hideFromIndex();
            }
            if ($path) {
                $computedMediaElement->path($path);
            }
            if ($usesClientOriginalName) {
                $computedMediaElement->storeAs(function ($request) {
                    return $request->media->getClientOriginalName();
                });
            }
            $computedMediaElementFields[] = $computedMediaElement;
        }

        return new self($name, $computedMediaElementFields);
    }
}
