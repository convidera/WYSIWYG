<?php

namespace HeinrichConvidera\WYSIWYG\App\Http\Requests;

use HeinrichConvidera\WYSIWYG\App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->request->has('id')) {
            // update single
            return [
                'id'    => $this->isValidUuidValidator('nullable'),
                'value' => 'nullable|string',
            ];
        }

        // update multiple
        return [
            '*'       => 'required|array|min:1',
            '*.id'    => $this->isValidUuidValidator('required'),
            '*.value' => 'nullable|string'
        ];
    }
}
