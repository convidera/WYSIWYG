<?php

namespace HeinrichConvidera\WYSIWYG\App\Http\Controllers;

use App\Http\Controllers\Controller;
use HeinrichConvidera\WYSIWYG\App\TextElement;
use HeinrichConvidera\WYSIWYG\App\Http\Requests\UpdateRequest;
use HeinrichConvidera\WYSIWYG\App\Http\Resources\TextElementResource;

class WYSIWYGController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, TextElement $textElement = null) {

        if ($textElement) {
            // update single
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
