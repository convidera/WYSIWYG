<?php

namespace HeinrichConvidera\WYSIWYG\App\Traits;

use HeinrichConvidera\WYSIWYG\App\TextElement;
use Illuminate\Database\Eloquent\Model;

trait HasDefaultTextContents
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

    protected static function boot()
    {
        parent::boot();

        self::created(get_called_class() . '@createDefaultTextKeys');
    }

    public function textElements() {
        return $this->morphMany(TextElement::class, 'text_elementable');
    }
}