<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index()
    {
        try {
            $data = Inquiry::with('inquiryReply', 'user')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
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
            $data = Inquiry::with('inquiryReply')->where('user_id', $validated['user_id'])->orderBy('created_at', 'desc')->get();
            if (!$data) {
                return response()->json(['message'=>'Failed to find Inquiries'], 402);
            }
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
                'title' => 'nullable',
                'message' => 'required',
            ]);
            $result = Inquiry::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to send Inquiry'], 402);
            }
            return response()->json(['message'=>'Inquiry sent successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function view(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:inquiries,id'
            ]);
            $data = Inquiry::with('user')->where('id', $validated['id'])->first();
            if (!$data) {
                return response()->json(['message'=>'Can not find Inquiry'], 404);
            }
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:inquiries,id'
            ]);
            $inquiry = Inquiry::find($validated['id']);
            $result = $inquiry->delete();
            if (!$result) {
                return response()->json(['message'=>'Failed to delete Inquiry'], 402);
            }
            return response()->json(['message'=>'Inquiry successfully deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
