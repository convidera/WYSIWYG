<?php

namespace Convidera\WYSIWYG\Nova\Fields;

use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class ComputedMediaElement extends Image
{
    public $showOnDetail = false;

    public function resolveAttribute($resource, $attribute)
    {
        return ($mediaElement = $resource->mediaElements()->key($attribute)->first()) ? $mediaElement->value : '';
    }

    public function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $key = $this->getEscapedKey($requestAttribute);
        if (is_null($file = $request->file($key)) || ! $file->isValid()) {
            return;
        }

        $model->id = $model->id ?? Uuid::uuid4()->toString();
        return $model->mediaElements()->updateOrCreate([
            'media_elementable_id' => $model->id,
            'key' => $attribute,
        ], [
            'media_elementable_id' => $model->id,
            'key' => $attribute,
            'value' => $this->storeFile($request)
        ]);
    }

    protected function storeFile($request)
    {
        if (! $this->storeAsCallback) {
            return $request->file($this->getEscapedKey($this->attribute))->store($this->storagePath, $this->disk);
        }

        return $request->file($this->getEscapedKey($this->attribute))->storeAs(
            $this->storagePath, call_user_func($this->storeAsCallback, $request), $this->disk
        );
    }

    protected function columnsThatShouldBeDeleted()
    {
        $attributes = [];

        if ($this->originalNameColumn) {
            $attributes[$this->originalNameColumn] = null;
        }

        if ($this->sizeColumn) {
            $attributes[$this->sizeColumn] = null;
        }

        return $attributes;
    }

    private function getEscapedKey(string $key) {
        return str_replace('.', '_', $key);
    }
}
