<?php

namespace Convidera\WYSIWYG\Http\Requests;

use Convidera\WYSIWYG\Http\Requests\BaseRequest;

class UpdateTextsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*'       => 'required|array|min:1',
            '*.id'    => $this->isValidTextElementUuidValidator('required'),
            '*.value' => 'nullable|string'
        ];
    }
}
