<?php

namespace Convidera\WYSIWYG\Http\Requests;

use Convidera\WYSIWYG\TextElement;
use Illuminate\Foundation\Http\FormRequest;
use Convidera\WYSIWYG\MediaElement;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function isValidTextElementUuidValidator($additionals = null) {
        return $this->isValidUuidValidator(function($uuid) {
            return TextElement::find($uuid);
        }, $additionals);
    }

    public function isValidMediaElementUuidValidator($additionals = null) {
        return $this->isValidUuidValidator(function($uuid) {
            return MediaElement::find($uuid);
        }, $additionals);
    }

    private function isValidUuidValidator($fnCheck, $additionals = null) {
        $arr = [
            'string',
            function ($attribute, $value, $fail) use ($fnCheck) {
                if ( ! $fnCheck($value)) {
                    $fail($attribute . ' is not a valid text element uuid.');
                }
            },
        ];

        if ($additionals) {
            array_push($arr, $additionals);
        }
        return $arr;
    }

    public function getMediaFileRules($file) {
        $mimeType = $file->getMimeType();

        if (strpos($mimeType, 'image') !== false) {
            return 'file|image';
        }

        if (strpos($mimeType, 'video') !== false) {
            return 'file';
        }
        
        // Unvalid! - Unknown mimeType.
        return [ function ($attribute, $value, $fail) use ($mimeType) {
            $fail("$attribute is not a valid media file. Unknown mime type: '$mimeType'");
        }];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public abstract function rules();
}
