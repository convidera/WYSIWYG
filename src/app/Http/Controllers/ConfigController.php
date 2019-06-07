<?php

namespace Convidera\WYSIWYG\Http\Controllers;

use App\Http\Controllers\Controller;;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function showConfig(Request $request) {
        $config = [
            'imageMaxSize' => $this->convertSizeStrToSizeObject(env('WYSIWYG_IMAGE_MAX_SIZE', '200K')),
            'videoMaxSize' => $this->convertSizeStrToSizeObject(env('WYSIWYG_VIDEO_MAX_SIZE', '40M')),
        ];
        return json_encode($config, JSON_FORCE_OBJECT);
    }

    private function convertSizeStrToSizeObject($size) {
        $sizeStr = $size;
        $unit = 0; // default unit: Byte

        $unitStr = substr($size, -1, 1);
        if (!is_numeric($unitStr)) {
            switch(strtoupper($unitStr)) {
                case 'B': $unit = 0; break;
                case 'K': $unit = 1; break;
                case 'M': $unit = 2; break;
                case 'G': $unit = 3; break;
                default: return null;
            }
            $sizeStr = substr($size, 0, -1);
        }

        return [
            'size'      => intval($sizeStr),
            'unit'      => $unit,
        ];
    }
}
