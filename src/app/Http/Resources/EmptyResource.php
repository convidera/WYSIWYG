<?php

namespace HeinrichConvidera\WYSIWYG\App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class EmptyResource extends Resource
{
    /**
     * Empty default constructor.
     */
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [];
    }
}
