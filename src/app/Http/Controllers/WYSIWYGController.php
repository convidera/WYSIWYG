<?php

namespace Convidera\WYSIWYG\Http\Controllers;

use App\Http\Controllers\Controller;
use Convidera\WYSIWYG\Http\Requests\UpdateMediaRequest;
use Convidera\WYSIWYG\Http\Requests\UpdateMediasRequest;
use Convidera\WYSIWYG\Http\Requests\UpdateTextRequest;
use Convidera\WYSIWYG\Http\Requests\UpdateTextsRequest;
use Convidera\WYSIWYG\Http\Resources\MediaElementResource;
use Convidera\WYSIWYG\MediaElement;
use Convidera\WYSIWYG\TextElement;
use Convidera\WYSIWYG\Http\Resources\TextElementResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function updateMedia(/*UpdateMedia*/Request $request, $mediaElement) {
        $mediaElement = MediaElement::findOrFail($mediaElement);
        $mediaElement->value = $request->file('file');
        $mediaElement->save();
        return MediaElementResource::make($mediaElement);
    }

    public function updateMedias(UpdateMediasRequest $request) {
        $ids   = $request->input('ids');
        $files = $request->file('files');

        return MediaElementResource::collection(collect(array_map(function($file, $id) {
            $mediaElement = MediaElement::findOrFail($id);
            $path = 'wysiwyg/media';
            $filename = $file->getClientOriginalName();
            $fullpath = "$path/$filename";
            Storage::disk('public')->putFileAs($path, $file, $filename);
            $mediaElement->value = $fullpath;
            $mediaElement->save();
            return $mediaElement;
        }, $files, $ids)));
    }
}
