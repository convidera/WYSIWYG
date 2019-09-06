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

class TextElement extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Convidera\WYSIWYG\TextElement';

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
     * Array of class types which should be displayed.
     *
     * @return array
     *
     * @example `return [ App\Nova\Homepage::class, App\Nova\StaticContent::class ];`
     */
    public function getTextElementableTypes()
    {
        static::$elementNovaClasses()->textElements;
    }
}
