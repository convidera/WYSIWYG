<?php

namespace Convidera\WYSIWYG\Traits;

use Convidera\WYSIWYG\Traits\ProvidesDefaultMediaElements;
use Convidera\WYSIWYG\Traits\ProvidesDefaultTextElements;
use Illuminate\Support\Str;
use Laravel\Nova\Nova;

trait HasElementClasses
{
    /**
     * The default element interfaces.
     *
     * @var array
     */
    private static $defaultElementTraits = [
        'textElements'  => ProvidesDefaultTextElements::class,
        'mediaElements' => ProvidesDefaultMediaElements::class,
    ];

    /**
     * The model class names that implements default elements.
     *
     * @var object
     */
    private static $elementModelClasses = [];

    /**
     * The nova class names whose models implement default elements.
     *
     * @var object
     */
    private static $elementNovaClasses  = [];

    /**
     * Get names of all models which implement default elements.
     *
     * @return object
     */
    protected static function elementModelClasses() {
        if ( ! empty(static::$elementModelClasses)) return static::$elementModelClasses;

        $autoloader = require base_path().'/vendor/autoload.php';
        static::$elementModelClasses = collect(array_keys($autoloader->getClassMap()))
            ->reduce(
                function($carry, string $class) {
                    if (Str::startsWith($class, 'App\\')) {
                        foreach (static::$defaultElementTraits as $elementType => $interface) {
                            if (is_subclass_of($class, $interface)) {
                                $carry->$elementType[] = $class;
                            }
                        }
                    }
                    return $carry;
                },
                (object) array_combine(
                    static::$defaultElementTraits, 
                    array_fill(0, count(static::$defaultElementTraits), null)
                )
            );
        return static::$elementModelClasses;
    }

    /**
     * Get names of all nova resources whose models implement default elements.
     *
     * @return object
     */
    protected static function elementNovaClasses() {
        if ( ! empty(static::$elementNovaClasses)) return static::$elementNovaClasses;

        static::$elementNovaClasses = collect(Nova::$resources)->reduce(
            function($carry, string $resource) {
                $class = $resource::$model;
                foreach (static::$defaultElementTraits as $elementType => $interface) {
                    if (is_subclass_of($class, $interface)) {
                        $carry->$elementType[] = $class;
                    }
                }
                return $carry;
            },
            (object) array_combine(
                static::$defaultElementTraits, 
                array_fill(0, count(static::$defaultElementTraits), null)
            )
        );
        return static::$elementNovaClasses;
    }
}
