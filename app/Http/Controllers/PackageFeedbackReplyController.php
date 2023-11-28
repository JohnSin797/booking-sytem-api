<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageFeedbackReply;

class PackageFeedbackReplyController extends Controller
{
    public function index()
    {
        try {

        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'package_feedback_id' => 'required|exists:package_feedback,id',
                'user_id' => 'required|exists:users,id',
                'reply' => 'required'
            ]);
            $result = PackageFeedbackReply::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to send reply'], 402);
            }
            return response()->json(['message'=>'Reply successfully sent'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
