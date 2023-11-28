<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageRating;

class PackageRatingController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'package_id' => 'required|exists:packages,id',
                'user_id' => 'required|exists:users,id',
                'stars' => 'required'
            ]);
            $result = PackageRating::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to rate'], 402);
            }
            return response()->json(['message'=>'Rating complete'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
