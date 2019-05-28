<?php

namespace Convidera\WYSIWYG\Http\Resources;

use Convidera\WYSIWYG\Http\Resources\EmptyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public static function make(...$parameters)
    {
        if (!$parameters[0]) {
            return new EmptyResource();
        }
        return call_user_func_array('parent::make', $parameters);
    }

    public function toObject($request = null)
    {
        return Response::make($this->response($request)->getData());
    }

    public function toJson($request = null)
    {
        return $this->response($request)->content();
    }
}
