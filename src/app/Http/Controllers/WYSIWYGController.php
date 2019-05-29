<?php

namespace Convidera\WYSIWYG\Http\Controllers;

use App\Http\Controllers\Controller;
use Convidera\WYSIWYG\TextElement;
use Convidera\WYSIWYG\Http\Requests\UpdateRequest;
use Convidera\WYSIWYG\Http\Resources\TextElementResource;

class WYSIWYGController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $textElement = null) {
        if ($textElement) {
            // update single
            $textElement = TextElement::findOrFail($textElement);
            $textElement->value = $request->input('value');
            $textElement->save();
            return TextElementResource::make($textElement);
        }

        // update multiple
        return TextElementResource::collection(collect($request->all())->map(function ($text) {
            $textElement = TextElement::findOrFail($text['id']);
            $textElement->value = $text['value'];
            $textElement->save();
            return $textElement;
        }));
    }
}
