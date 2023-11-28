<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public static function convertToBase64()
    {
        $imagePath = public_path('images/default-img.jpg');
        
        if (File::exists($imagePath)) {
            $imageData = file_get_contents($imagePath);
            $base64String = base64_encode($imageData);

            return $base64String;
        } else {
            return '';
        }
    }
}
