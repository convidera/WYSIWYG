<?php

namespace Convidera\WYSIWYG\Nova;

use Convidera\WYSIWYG\Nova\Resource;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Wehaa\Liveupdate\Liveupdate;

abstract class TextElement extends Resource
{
    /**
     * Array of class types which should be displayed.
     *
     * @return array
     *
     * Example: return [ App\Nova\Homepage::class, App\Nova\StaticContent::class ]
     */
    public abstract function getTextElementableTypes();

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    public static $indexDefaultOrder = [
        'key' => 'asc'
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Convidera\WYSIWYG\TextElement';

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
            Select::make('type', 'type')->options([
                'plain' => 'plain',
                'markdown' => 'markdown',
            ])->fillUsing(function () {
                return ;
            })->displayUsingLabels(),


            NovaDependencyContainer::make([
                Textarea::make('Value'),
            ])->dependsOn('type', 'plain'),

            NovaDependencyContainer::make([
                Markdown::make('Value'),
            ])->dependsOn('type', 'markdown'),

            Liveupdate::make('Value')->onlyOnIndex(),
            MorphTo::make('TextElementable')->types($this->getTextElementableTypes()),
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
