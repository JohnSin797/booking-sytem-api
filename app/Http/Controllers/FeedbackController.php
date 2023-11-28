<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        try {
            $data = Feedback::with('replies')->orderBy('created_at', 'desc')->get();
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
                'user_id' => 'required|exists:users,id',
                'comment' => 'required'
            ]);
            $result = Feedback::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to send feedback'], 402);
            }
            return response()->json(['message'=>'Feedback sent'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
