<?php

namespace Convidera\WYSIWYG\Traits;

use Convidera\WYSIWYG\TextElement;
use Illuminate\Database\Eloquent\Model;

trait HasDefaultTextElements
{
    public static function createDefaultTextKeys(Model $model)
    {
        foreach (self::getDefaultTextKeys() as $defaultTextKey) {
            $model->textElements()->firstOrCreate([
                'key' => $defaultTextKey
            ]);
        }
    }

    public static function getDefaultTextKeys()
    {
        return self::$defaultTextKeys;
    }

    protected static function bootHasDefaultTextElements()
    {
        self::created(get_called_class() . '@createDefaultTextKeys');
    }

    public function textElements() {
        return $this->morphMany(TextElement::class, 'text_elementable');
    }
}
