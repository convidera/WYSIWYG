<?php

namespace Convidera\WYSIWYG\Traits;

use Illuminate\Database\Eloquent\Model;

interface ProvidesDefaultTextContents
{
    public function textElements();

    public static function createDefaultTextKeys(Model $model);
}
