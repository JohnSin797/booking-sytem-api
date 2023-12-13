<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageFeedbackReply;
use App\Models\Package;

class PackageFeedbackReplyController extends Controller
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
            $data = Package::with('booking', 'packageItem.product', 'rating', 'packageFeedbacks.user.packageRating', 'packageFeedbacks.packageFeedbackReply.user.packageRating')->whereHas('booking', function ($query) use($validated) {
                $query->whereHas('user', function ($q) use($validated) {
                    $q->where('id', $validated['user_id']);
                });
            })->orderBy('created_at', 'desc')->get();
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
