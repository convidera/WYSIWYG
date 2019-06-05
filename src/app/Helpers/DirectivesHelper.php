<?php

namespace Convidera\WYSIWYG\Helpers;

final class DirectivesHelper
{
    public static $imageTagStack = [];

    private function __construct() { }

    /**
     * Create parse result object.
     * 
     * @param {object} $data     element data
     * @param {array}  $options  custom display options
     * @param {array}  $options  default display options
     */
    private static function createReturnObject($data, array $options, array $defaults) {
        $globalDefaults = [
            'changeable' => true,
            'additionalClasses' => '',
            'additionalAttributes' => '',
        ];
        return [
            'data' => $data,
            'options' => (object) array_merge($defaults, $options, $globalDefaults)
        ];
    }

    /**
     * Parse @text and @markdown directive arguments.
     * 
     * @param {object} $data     text element data
     * @param {array}  $options  custom display options
     */
    public static function parseTextDirectiveArguments($data, array $options) {
        $defaults = [
            'tag' => 'span',
        ];
        return self::createReturnObject($data, $options, $defaults);
    }

    /**
     * Parse @image directive arguments.
     * 
     * @param {object} $data     media (image) element data
     * @param {array}  $options  custom display options
     */
    public static function parseImageDirectiveArguments($data, array $options) {
        $defaults = [
            'tag' => 'img',
            'asBackgroundImage' => false,
            'closeTag' => false,
        ];
        return self::createReturnObject($data, $options, $defaults);
    }
}
