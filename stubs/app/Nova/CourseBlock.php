<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;

class CourseBlock extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \SteadfastCollective\Summit\Models\CourseBlock::class;

    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
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
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Title')
                ->rules('required', 'string', 'max:255'),
            
            Slug::make('Slug')
                ->from('Title')
                ->rules('required')
                ->creationRules('unique:course_blocks,slug')
                ->updateRules('unique:course_blocks,slug,{{resourceId}}'),

            Textarea::make('Description')
                ->hideFromIndex()
                ->alwaysShow()
                ->rules('nullable'),

            Number::make('Estimated Length')
                ->help('In seconds.')
                ->rules('nullable', 'integer'),

            File::make('File Download', 'download_file_path')
                ->path('block-downloads')
                ->hideFromIndex()
                ->rules('nullable'),

            BelongsTo::make('Course', 'course', \App\Nova\Course::class),

            MorphMany::make('Videos', 'videos', \App\Nova\Video::class),

            DateTime::make('Available From'),
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
