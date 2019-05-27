<?php

namespace HeinrichConvidera\WYSIWYG\App\Http\Resources;

use App\Http\Resources\Resource;

class TextElementResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'key'   => $this->key,
            'value' => $this->value,
        ];
    }
}
