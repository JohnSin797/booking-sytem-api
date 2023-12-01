<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        try {
            $data = About::first();
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'description' => 'required'
            ]);
            $about = new About();
            $about->description = $validated['description'];
            $result = $about->save();
            if (!$result) {
                return response()->json(['message'=>'Failed to save About'], 402);
            }
            return response()->json(['message'=>'About saved successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'description' => 'required'
            ]);
            $about = About::first();
            $result = $about->update($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to update About'], 402);
            }
            return response()->json(['message'=>'About updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
