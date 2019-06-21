<?php

namespace Convidera\WYSIWYG\Traits;

use Convidera\WYSIWYG\MediaElement;
use Illuminate\Database\Eloquent\Model;

trait HasDefaultMediaElements
{
    public static function createDefaultMediaKeys(Model $model)
    {
        foreach (self::getDefaultMediaKeys() as $defaultMediaKey) {
            $model->mediaElements()->firstOrCreate([
                'key' => $defaultMediaKey
            ]);
        }
    }

    public static function getDefaultMediaKeys()
    {
        return self::$defaultMediaKeys;
    }

    protected static function bootHasDefaultMediaElements()
    {
        self::created(get_called_class() . '@createDefaultMediaKeys');
    }

    public function mediaElements() {
        return $this->morphMany(MediaElement::class, 'media_elementable')->orderBy('key');
    }
}
