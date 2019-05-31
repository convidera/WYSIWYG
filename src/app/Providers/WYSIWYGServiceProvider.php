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
        $this->app->make('Convidera\WYSIWYG\Http\Controllers\MarkdownParserController');

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

        $this->directives();
    }

    private function directives() {
        /**
         * @param data translation
         * @param tag surrounding tag
         * @param editable force normal text
         */
        Blade::directive('text', function($expression) {
            $data = $this->parseExpression($expression);
            $dataStr = $this->expressionDataToString($data);
            return "<?php echo view('wysiwyg::text-element', $dataStr) ?>";
        });

        /**
         * @param data translation
         * @param tag surrounding tag
         * @param editable force normal text
         */
        Blade::directive('markdown', function($expression) {
            $data = $this->parseExpression($expression);
            if ( array_key_exists('data', $data) && $data['data']) {
                $dataStr = $this->expressionDataToString($data);
                return "<?php echo view('wysiwyg::markdown-element', $dataStr); ?>";
            }
            return "<?php ob_start(); ?>";
        });
        Blade::directive('endmarkdown', function() {
            return "<?php echo Illuminate\Mail\Markdown::parse(ob_get_clean()); ?>";
        });
    }

    private function parseExpression($expression) {
        if ( ! $expression) return [];
        $parameters = array_map(function($parameter) {
            return trim($parameter);
        } , explode(',', $expression));
        if (count($parameters) < 1) return [];

        $data = $parameters[0];
        $data = (strtoupper($data) == 'NULL') ? '' : $data;
        $tag = (isset($parameters[1])) ? $parameters[1] : 'span';
        $tag = (strtoupper($tag) == 'NULL') ? '' : $tag;
        $editable = isset($parameters[2]) ? $parameters[2] : 'true';
        $editable = (strtoupper($editable) == 'FALSE') ? 'false' : true;

        return [ 'data' => $data, 'tag' => '$tag', 'editable' => $editable ];
    }

    private function expressionDataToString($data) {
        return "[ 'data' => $data->data, 'tag' => '$data->tag', 'editable' => $data->editable ];"
    }

    private function arrayToString($array, $str = '') {
        foreach ($array as $key => $item) {
            $str .= "$key => $item <br>";
        }
    }
}
