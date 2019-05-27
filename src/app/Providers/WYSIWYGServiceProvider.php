<?php

namespace HeinrichConvidera\WYSIWYG\App\Providers;

use HeinrichConvidera\WYSIWYG\App\Http\Resources\Resource;
use HeinrichConvidera\WYSIWYG\App\Http\Resources\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class WYSIWYGServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // controller
        $this->app->make('HeinrichConvidera\WYSIWYG\Controllers\WYSIWYGController');

        // views
        $this->loadViewsFrom(__DIR__.'/views', 'wysiwyg');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/wysiwyg'),
        ], 'routes');
        $this->publishes([
            __DIR__ . '/migrations' => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/assets' => public_path('vendor/wysiwyg'),
        ], 'public');

        Resource::withoutWrapping();
        Response::displayTextElementKeys(env('DISPLAY_TEXT_ELEMENT_KEYS', false));

        /**
         * @param data translation
         * @param tag surrounding tag
         * @param editable force normal text
         */
        Blade::directive('text', function($expression) {
            $parameters = array_map(function($parameter) {
                return trim($parameter);
            } , explode(',', $expression));
            $data = $parameters[0];
            $tag = (isset($parameters[1])) ? $parameters[1] : 'span';
            $tag = ($tag == 'null') ? '' : $tag;
            $editable = isset($parameters[2]) ? $parameters[2] : 'true';
            return "<?php echo view('text-element', [ 'data' => $data, 'tag' => '$tag', 'editable' => $editable ]) ?>";
        });
    }
}
