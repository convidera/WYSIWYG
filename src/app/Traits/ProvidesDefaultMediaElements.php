<?php

namespace Convidera\WYSIWYG\Traits;

use Illuminate\Database\Eloquent\Model;

interface ProvidesDefaultMediaElements
{
    public function textElements();

    public static function createDefaultTextKeys(Model $model);
}
