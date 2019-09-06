<?php

namespace Convidera\WYSIWYG\Nova;

use App\Nova\TextElement;
use Convidera\WYSIWYG\Nova\Panles\ComputedTextElementPanel;
use Convidera\WYSIWYG\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;

class MediaElement extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Convidera\WYSIWYG\MediaElement';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Key')->sortable(),
            Image::make('Value')->storeAs(function ($request) {
                return $request->value->getClientOriginalName();
            }),
            MorphTo::make('MediaElementable')->types($this->getMediaElementableTypes()),
            ComputedTextElementPanel::make('Text Elements', $this->resource, 10),

            MorphMany::make('Text Elements', 'textElements', TextElement::class),
        ];
    }

    /**
     * Array of class types which should be displayed.
     *
     * @return array
     *
     * @example `return [ App\Nova\Homepage::class, App\Nova\StaticContent::class ];`
     */
    public function getMediaElementableTypes()
    {
        static::$elementNovaClasses()->mediaElements;
    }
}
