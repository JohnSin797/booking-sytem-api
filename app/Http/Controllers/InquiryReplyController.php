<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InquiryReply;

class InquiryReplyController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'inquiry_id' => 'required|exists:inquiries,id',
                'message' => 'required'
            ]);
            $result = InquiryReply::create([
                'inquiry_id' => $validated['inquiry_id'],
                'message' => $validated['message'],
                'status' => 'unread'
            ]);
            if (!$result) {
                return response()->json(['message'=>'Failed to send reply'], 402);
            }
            return response()->json(['message'=>'Reply successfully sent'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:inquiry_replies,id'
            ]);
            $data = InquiryReply::with('inquiry.user')->where('id', $validated['id'])->first();
            if (!$data) {
                return response()->json(['message'=>'Can not find reply'], 402);
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
