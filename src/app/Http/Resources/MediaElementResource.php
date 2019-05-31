<?php

namespace Convidera\WYSIWYG\Http\Resources;

use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Storage;

class MediaElementResource extends Resource
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
            'url'   => $this->media_path ? Storage::disk()->url($this->media_path) : null,
        ];
    }
}
