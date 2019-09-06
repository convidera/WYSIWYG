<?php

namespace Convidera\WYSIWYG\Traits;

use Convidera\WYSIWYG\Traits\ProvidesDefaultElements;
use Illuminate\Database\Eloquent\Model;

interface ProvidesDefaultTextElements extends ProvidesDefaultElements
{
    public function textElements();

    public static function createDefaultTextKeys(Model $model);
}
