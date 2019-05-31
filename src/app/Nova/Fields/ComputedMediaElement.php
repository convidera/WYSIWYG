<?php

namespace Convidera\WYSIWYG\Nova\Fields;

use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class ComputedMediaElement extends Image
{
    public $showOnDetail = false;
    protected $length;

    public function resolveAttribute($resource, $attribute)
    {
        return ($mediaElement = $resource->mediaElements()->key($attribute)->first()) ? $mediaElement->value : '';
    }

    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $key = $this->getEscapedKey($requestAttribute);
        if ($request->exists($key)) {
            $value = $request[$key];

            $isNull = false;

            if ($this->nullable) {
                $isNull = is_callable($this->nullValues)
                    ? ($this->nullValues)($value)
                    : in_array($value, (array) $this->nullValues);
            }

            $mediaElement = $model->mediaElements()->key($attribute)->first();

            if (!$mediaElement) {
                $model->id = $model->id ?? Uuid::uuid4()->toString();
                return $model->mediaElements()->create([
                    'media_elementable_id' => $model->id,
                    'key' => $attribute,
                    'url' => $isNull ? null : $value
                ]);
            }

            $mediaElement->update([
                'url' => $isNull ? null : $value
            ]);
        }
    }

    private function getEscapedKey(string $key) {
        return str_replace('.', '_', $key);
    }
}
