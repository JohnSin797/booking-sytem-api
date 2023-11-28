<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        try {

        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);
            $star = Rating::where('user_id', $validated['user_id'])->first();
            if (!$star) {
                return response()->json(['message'=>'Failed'], 402);
            }
            return response()->json([
                'message' => 'OK',
                'data' => $star
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'stars' => 'required',
                'user_id' => 'required|exists:users,id'
            ]);
            $result = Rating::updateOrCreate(['user_id'=>$validated['user_id']], $validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to save rating. Please try again.'], 402);
            }
            return response()->json(['message'=>'Thank you for rating us'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
