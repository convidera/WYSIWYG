<?php

namespace Convidera\WYSIWYG\Http\Controllers;

use App\Http\Controllers\Controller;
use Convidera\WYSIWYG\Http\Requests\MarkdownParseRequest;
use Illuminate\Mail\Markdown;

class MarkdownParserController extends Controller
{
    /**
     * Parse raw markdown to html
     */
    public function parse(MarkdownParseRequest $request) {
        return Markdown::parse($request->input('data'));
    }
}
