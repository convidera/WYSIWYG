<?php

namespace Convidera\WYSIWYG\Nova\Fields;

use Ramsey\Uuid\Uuid;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ComputedTextElement extends Text
{
    public $showOnDetail = false;
    protected $length;

    public function resolveAttribute($resource, $attribute)
    {
        return ($textElement = $resource->textElements()->key($attribute)->first()) ? $textElement->value : '';
    }

    public function resolveForDisplay($resource, $attribute = null)
    {
        parent::resolveForDisplay($resource, $attribute);
        $split = explode(' ', $this->value);
        $this->value = $this->length && count($split) > $this->length ? implode(' ', array_slice($split, 0, $this->length)) . ' ...' : $this->value;
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

            $model->id = $model->id ?? Uuid::uuid4()->toString();
            return $model->textElements()->updateOrCreate([
                'text_elementable_id' => $model->id,
                'key' => $this->name,
            ],[
                'text_elementable_id' => $model->id,
                'key' => $this->name,
                'value' => $isNull ? null : $value
            ]);
        }
    }

    private function getEscapedKey(string $key) {
        return str_replace('.', '_', $key);
    }

    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

}
