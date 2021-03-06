<?php

namespace Convidera\WYSIWYG\Providers;

use App\Console\Commands\UpdateElementables;
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

        //commands
        $this->commands([
            UpdateElementables::class
        ]);
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
         * @param {object} $data     text element data
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'span',
         *                                  'editable' => true
         *                              ];
         */
        Blade::directive('textraw', function($expression) {
            return $this->textElementCode($expression);
        });
        /**
         * @param {string} $key      text element key
         * @param {string} $var      varibale where the key is stored (default: $data)
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'span',
         *                                  'editable' => true
         *                              ];
         */
        Blade::directive('text', function($expression) {
            $preparedExpression = $this->replaceKeyWithElement($expression, 'textElement');
            return $this->textElementCode($preparedExpression);
        });

        /**
         * @param {object} $data     text element data
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'span',
         *                                  'editable' => true
         *                              ];
         */
        Blade::directive('markdownraw', function($expression) {
            return $this->markdownElementCode($expression);
        });
        /**
         * @param {string} $key      text element key
         * @param {string} $var      varibale where the key is stored (default: $data)
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'span',
         *                                  'editable' => true
         *                              ];
         */
        Blade::directive('markdown', function($expression) {
            $preparedExpression = $this->replaceKeyWithElement($expression, 'textElement');
            return $this->markdownElementCode($preparedExpression);
        });

        /**
         * @param {object} $data     image element data
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'img',
         *                                  'editable' => true,
         *                                  'asBackgroundImage' => false,
         *                                  'closeTag' => true
         *                              ];
         */
        Blade::directive('imageraw', function($expression) {
            return $this->imageElementCode($expression);
        });
        Blade::directive('endimageraw', function() {
            return '<?php echo "</" . array_pop(\Convidera\WYSIWYG\Helpers\DirectivesHelper::$tagStack) . ">" ?>';;
        });
        /**
         * @param {string} $key      image element key
         * @param {string} $var      varibale where the key is stored (default: $data)
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'img',
         *                                  'editable' => true,
         *                                  'asBackgroundImage' => false,
         *                                  'closeTag' => true
         *                              ];
         */
        Blade::directive('image', function($expression) {
            $preparedExpression = $this->replaceKeyWithElement($expression, 'mediaElement');
            return $this->imageElementCode($preparedExpression);
        });
        Blade::directive('endimage', function() {
            return '<?php echo "</" . array_pop(\Convidera\WYSIWYG\Helpers\DirectivesHelper::$tagStack) . ">" ?>';
        });
    }

    private function replaceKeyWithElement($expression, $fnName) {
        $pattern = '/^\s*(\'(?<key1>.*?)\'|"(?<key2>.*?)")\s*(,\s*(?<var>\$.+?){0,1})?\s*((?<options>,\s*.*)\s*)?$/s';
        $matches = [];
        preg_match($pattern, $expression, $matches, PREG_OFFSET_CAPTURE, 0);

        $key = (array_key_exists('key1', $matches) && !empty($matches['key1'][0])) ? $matches['key1'][0] : $matches['key2'][0];
        $var = (array_key_exists('var', $matches) && !empty($matches['var'][0]) && $matches['var'][0] != 'null') ? $matches['var'][0] : '$data';
        $options = (array_key_exists('options', $matches) && !empty($matches['options'][0]) && $matches['var'][0] != 'null') ? $matches['options'][0] : '';

        return "${var}->${fnName}('${key}') ?? ${var}${options}";
    }

    private function textElementCode($expression) {
        return '<?php
            $args = \Convidera\WYSIWYG\Helpers\DirectivesHelper::parseTextDirectiveArguments(' . $expression . ');
            echo view("wysiwyg::text-element", $args);
        ?>';
    }

    private function markdownElementCode($expression) {
        return '<?php
            $args = \Convidera\WYSIWYG\Helpers\DirectivesHelper::parseMarkdownDirectiveArguments(' . $expression . ');
            echo view("wysiwyg::markdown-element", $args);
        ?>';
    }

    private function imageElementCode($expression) {
        return '<?php
            $args = \Convidera\WYSIWYG\Helpers\DirectivesHelper::parseImageDirectiveArguments(' . $expression . ');
            echo view("wysiwyg::image-element", $args);
        ?>';
    }
}
