<?php

namespace Convidera\WYSIWYG\Providers;

use Convidera\WYSIWYG\Http\Resources\Resource;
use Convidera\WYSIWYG\Http\Resources\Response;
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
        $this->app->make('Convidera\WYSIWYG\Http\Controllers\WYSIWYGController');

        // views
        $this->loadViewsFrom(realpath(__DIR__ . '/../../resources/views'), 'wysiwyg');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
        Response::displayTextElementKeys(env('DISPLAY_TEXT_ELEMENT_KEYS', false));

        $this->loadRoutesFrom(realpath(__DIR__ . '/../../routes/api.php'));
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../database/migrations'));

        $this->publishes([
            realpath(__DIR__ . '/../../resources/views') => resource_path('views/vendor/wysiwyg'),
        ], 'routes');
        $this->publishes([
            realpath(__DIR__ . '/../../database/migrations') => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            realpath(__DIR__ . '/../../../dist') => public_path('vendor/wysiwyg'),
        ], 'public');

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
            return "<?php echo view('wysiwyg::text-element', [ 'data' => $data, 'tag' => '$tag', 'editable' => $editable ]) ?>";
        });
    }
}
