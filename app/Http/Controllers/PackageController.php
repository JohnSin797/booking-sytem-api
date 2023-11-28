<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Product;
use App\Models\Feedback;

class PackageController extends Controller
{
    public function index()
    {
        try {
            $data = Package::with('packageItem.product', 'rating')->where('package_type', 'fixed')->where('status', 'active')->get();
            $feedback = Feedback::with('user.rating', 'replies.user.rating')->orderBy('created_at', 'desc')->get();
            if (!$data) {
                return response()->json(['message'=>'Failed'], 402);
            }
            return response()->json([
                'message' => 'OK',
                'data' => $data,
                'feedback' => $feedback
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:packages,id'
            ]);
            $data = Package::with('rating', 'packageItem.product', 'packageFeedbacks.user.packageRating', 'packageFeedbacks.packageFeedbackReply.user.packageRating')->where('id', $validated['id'])->first();
            $products = Product::where('status', 'active')->get();
            return response()->json([
                'message' => 'OK',
                'data' => $data,
                'products' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'product_type' => 'required',
                'quantity' => 'required',
                'total_price' => 'required',
                'product_id' => 'required',
                'status' => 'required',
                'image' => 'required',
                'description' => 'nullable',
                'capital' => 'required',
            ]);
            $pack = Package::create([
                'name' => $validated['name'],
                'package_type' => 'fixed',
                'product_type' => $validated['product_type'],
                'quantity' => $validated['quantity'],
                'total_price' => $validated['total_price'],
                'status' => $validated['status'],
                'image' => $validated['image'],
                'description' => $validated['description'],
                'capital' => $validated['capital'],
            ]);
            foreach ($validated['product_id'] as $key => $value) {
                PackageItem::create([
                    'package_id' => $pack->id,
                    'product_id' => $value
                ]);
            }
            return response()->json(['message'=>'Package successfully created'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:packages,id',
                'name' => 'required',
                'product_type' => 'required',
                'quantity' => 'required',
                'total_price' => 'required',
                'product_id' => 'required|array',
                'status' => 'required',
                'image' => 'required',
                'description' => 'nullable',
                'capital' => 'required|numeric|min:1',
            ]);
            $package = Package::find($validated['id']);
            $result = $package->update([
                'name' => $validated['name'],
                'product_type' => $validated['product_type'],
                'quantity' => $validated['quantity'],
                'total_price' => $validated['total_price'],
                'status' => $validated['status'],
                'image' => $validated['image'],
                'description' => $validated['description'],
                'capital' => $validated['capital'],
            ]);
            if (!$result) {
                return response()->json(['message'=>'Failed to update Package'], 402);
            }
            foreach ($validated['product_id'] as $key => $value) {
                PackageItem::updateOrCreate([
                    'package_id' => $package->id,
                    'product_id' => $value
                ]);
            }
            PackageItem::where('package_id', $package->id)->whereNotIn('product_id', $validated['product_id'])->delete();
            return response()->json(['message'=>'Package successfully updated'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function archive()
    {
        try {
            $data = Package::onlyTrashed()->get();
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

    public function restore(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:packages,id'
            ]);
            $result = Package::onlyTrashed()->find($validated['id'])->restore();
            if (!$result) {
                return response()->json(['message'=>'Failed to restore Package'], 402);
            }
            return response()->json(['message'=>'Package successfully restored'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:packages,id'
            ]);
            $result = Package::onlyTrashed()->find($validated['id'])->forceDelete();
            if (!$result) {
                return response()->json(['message'=>'Failed to delete Package'], 402);
            }
            return response()->json(['message'=>'Package has been deleted permanently'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:packages,id'
            ]);
            $result = Package::find($validated['id'])->delete();
            if (!$result) {
                return response()->json(['message'=>'Failed to delete Package'], 402);
            }
            return response()->json(['message'=>'Package successfully deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
