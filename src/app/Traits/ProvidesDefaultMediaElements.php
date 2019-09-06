<?php

namespace Convidera\WYSIWYG\Traits;

use Convidera\WYSIWYG\Traits\ProvidesDefaultElements;
use Illuminate\Database\Eloquent\Model;

interface ProvidesDefaultMediaElements extends ProvidesDefaultElements
{
    public function mediaElements();

    public static function createDefaultMediaKeys(Model $model);
}
