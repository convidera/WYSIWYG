<?php

namespace Convidera\WYSIWYG\Traits;

use Illuminate\Database\Eloquent\Model;

interface ProvidesDefaultMediaElements
{
    public function mediaElements();

    public static function createDefaultMediaKeys(Model $model);
}
