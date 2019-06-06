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
         *                                  'asBackgroundImage' => false
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
         * @param {array}  $options  custom display options e.g.: [
         *                                  'tag' => 'img',
         *                                  'editable' => true,
         *                                  'asBackgroundImage' => false
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
        // "'key', [ 'options' => true ]"  ->  "$data->xxxx('key'), [ 'options' => true ]"
        // '"key", [ "options" => true ]'  ->  '$data->xxxx("key"), [ "options" => true ]'
        // xxxx e.g.: mediaElement, textElement etc. (source Response)
        $pattern = '/(\'(.*?)\'|"(.*?)")\s*(,)?/';
        $matches = [];
        preg_match($pattern, $expression, $matches, PREG_OFFSET_CAPTURE, 0);
        $replace = '$data->' . $fnName . '($1)$4';
        return preg_replace($pattern, $replace, $expression, 1);
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
