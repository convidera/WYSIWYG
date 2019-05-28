<?php

namespace Convidera\WYSIWYG\Http\Requests;

use Convidera\WYSIWYG\TextElement;
use Illuminate\Foundation\Http\FormRequest;

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

    public function isValidUuidValidator($additionals = null) {
        $arr = [
            'string',
            function ($attribute, $value, $fail) {
                if ( ! TextElement::find($value)) {
                    $fail($attribute . ' is not a valid text element uuid.');
                }
            },
        ];

        if ($additionals) {
            array_push($arr, $additionals);
        }

        return $arr;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public abstract function rules();
}
