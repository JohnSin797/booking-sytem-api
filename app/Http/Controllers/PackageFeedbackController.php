<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageFeedback;
use App\Models\Package;
use App\Models\User;

class PackageFeedbackController extends Controller
{
    public function index()
    {
        try {
            // $data = PackageFeedback::with('packageItem.product', 'rating', 'packageFeedbacks.packageFeedbackReply')->orderBy('created_at', 'desc')->get();
            $data = Package::with('packageItem.product', 'rating', 'packageFeedbacks.user.packageRating', 'packageFeedbacks.packageFeedbackReply.user.packageRating')->get();
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
                'package_id' => 'required|exists:packages,id',
                'user_id' => 'required|exists:users,id',
                'comment' => 'required'
            ]);
            $user = User::find($validated['user_id']);
            if ($user->packageFeedback()->whereHas('package', function($query) use($validated) {
                $query->where('id', $validated['package_id']);
            })->doesntExist()) {
                return response()->json(['message'=>'Booking has not been confirmed yet'], 403);
            }
            $result = PackageFeedback::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to send feedback'], 402);
            }
            return response()->json([
                'message' => 'Feedback successfully sent'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id'
            ]);
            $data = Package::with('packageItem.product', 'rating', 'packageFeedbacks.packageFeedbackReply')->where('user_id', $validated['id'])->orderBy('created_at', 'desc')->get();
            if (!$data) {
                return response()->json(['message'=>'No data'], 402);
            }
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
