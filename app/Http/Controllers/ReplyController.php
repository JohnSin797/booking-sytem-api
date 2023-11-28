<?php

namespace App\Http\Controllers;
use App\Models\Reply;

use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request) 
    {
        try {
            $validated = $request->validate([
                'reply_to' => 'required|exists:feedback,id',
                'user_id' => 'required|exists:users,id',
                'comment' => 'required'
            ]);
            $result = Reply::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to send reply'], 402);
            }
            return response()->json(['message'=>'Reply sent'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
