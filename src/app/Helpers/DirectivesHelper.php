<?php

namespace Convidera\WYSIWYG\Helpers;

final class DirectivesHelper
{
    /**
     * @var {array} Stack of tags to close.
     */
    public static $tagStack = [];


    /**
     * No instance needed!
     */
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
            'options' => (object) array_merge($globalDefaults, $defaults, $options)
        ];
    }


    /**
     * Parse @text directive arguments.
     * 
     * @param {object} $data     text element data
     * @param {array}  $options  custom display options
     */
    public static function parseTextDirectiveArguments($data, array $options = []) {
        $defaults = [
            'tag' => 'span',
        ];
        return self::createReturnObject($data, $options, $defaults);
    }

    /**
     * Parse @markdown directive arguments.
     * 
     * @param {object} $data     text (markdown) element data
     * @param {array}  $options  custom display options
     */
    public static function parseMarkdownDirectiveArguments($data, array $options = []) {
        return self::parseTextDirectiveArguments($data, $options);
    }

    /**
     * Parse @image directive arguments.
     * 
     * @param {object} $data     media (image) element data
     * @param {array}  $options  custom display options
     */
    public static function parseImageDirectiveArguments($data, array $options = []) {
        $defaults = [
            'tag' => 'img',
            'asBackgroundImage' => false,
            'closeTag' => false,
        ];
        $result = self::createReturnObject($data, $options, $defaults);

        if (!$result["options"]->closeTag) {
            // save closing tag for later - @endimage
            array_push(self::$tagStack, $result["options"]->tag);
        }

        return $result;
    }
}
