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

abstract class MediaElement extends Resource
{
    /**
     * Array of class types which should be displayed.
     *
     * @return array
     *
     * Example: return [ App\Nova\Homepage::class, App\Nova\StaticContent::class ]
     */
    public abstract function getMediaElementableTypes();

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Convidera\WYSIWYG\MediaElement';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'key';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'key', 'value',
    ];

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
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
