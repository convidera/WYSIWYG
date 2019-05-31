<?php

namespace Convidera\WYSIWYG\Http\Controllers;

use App\Http\Controllers\Controller;;

use Convidera\WYSIWYG\Http\Requests\UpdateMediaRequest;
use Convidera\WYSIWYG\Http\Requests\UpdateMediasRequest;
use Convidera\WYSIWYG\Http\Requests\UpdateTextRequest;
use Convidera\WYSIWYG\Http\Requests\UpdateTextsRequest;
use Convidera\WYSIWYG\Http\Resources\MediaElementResource;
use Convidera\WYSIWYG\MediaElement;
use Convidera\WYSIWYG\TextElement;
use Convidera\WYSIWYG\Http\Resources\TextElementResource;

class WYSIWYGController extends Controller
{
    public function updateText(UpdateTextRequest $request, $textElement) {
        $textElement = TextElement::findOrFail($textElement);
        $textElement->value = $request->input('value');
        $textElement->save();
        return TextElementResource::make($textElement);
    }

    public function updateTexts(UpdateTextsRequest $request) {
        return TextElementResource::collection(collect($request->all())->map(function ($text) {
            $textElement = TextElement::findOrFail($text['id']);
            $textElement->value = $text['value'];
            $textElement->save();
            return $textElement;
        }));
    }

    public function updateMedia(UpdateMediaRequest $request, $mediaElement) {
        $mediaElement = MediaElement::findOrFail($mediaElement);
        $mediaElement->value = $request->file('file');
        $mediaElement->save();
        return MediaElementResource::make($mediaElement);
    }

    public function updateMedias(UpdateMediasRequest $request) {
        return MediaElementResource::collection(collect($request->all())->map(function ($text) {
            $mediaElement = MediaElement::findOrFail($text['id']);
            $mediaElement->value = $text['value'];
            $mediaElement->save();
            return $mediaElement;
        }));
    }
}
