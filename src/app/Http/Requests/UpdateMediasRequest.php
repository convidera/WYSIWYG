<?php

namespace Convidera\WYSIWYG\Http\Requests;

use Convidera\WYSIWYG\Http\Requests\BaseRequest;

class UpdateMediasRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $size = count($this->input('ids')) ?: 1;

        $rules = [];
        $rules['ids'] = "required|array|min:$size";
        $rules['ids.*'] = $this->isValidMediaElementUuidValidator('required');

        $rules['files'] = "required|array|min:$size";
        if ( ! $this->hasFile('files')) {
            // Unvalid! - No files in attribute files.
            $rules['files.0'] = [ function ($attribute, $value, $fail) {
                $fail("$attribute is not a valid file array.");
            }];
            return $rules;
        }

        $files = $this->file('files');
        if (is_array($files)) {
            foreach ($files as $index => $file) {
                $rules["files.$index"] = $this->getMediaFileRules($file);
            }
        }

        return $rules;
    }
}
