<?php

namespace Convidera\WYSIWYG\Http\Requests;

use Convidera\WYSIWYG\Http\Requests\BaseRequest;

class UpdateMediaRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['ids'] = $this->isValidMediaElementUuidValidator('required');

        if ( ! $this->hasFile('file')) {
            // Unvalid! - No files in attribute files.
            $rules['file'] = [ function ($attribute, $value, $fail) {
                $fail("$attribute is not a valid file array.");
            }];
            return $rules;
        }

        $file = $this->file('file');
        $rules['file'] = $this->getMediaFileRules($file);
        return $rules;
    }
}
