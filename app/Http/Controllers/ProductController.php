<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'keyword' => 'nullable'
            ]);
            $data = Product::where('name', 'LIKE', '%'.$validated['keyword'].'%')->orWhere('product_type', 'LIKE', '%'.$validated['keyword'].'%')->get();
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function index()
    {
        try {
            $data = Product::all();
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
                'product_type' => 'required'
            ]);
            $products = Product::where('product_type', $validated['product_type'])->get();
            if (count($products) == 0) {
                return response()->json([
                    'message' => 'No Item Found'
                ], 402);
            }
            if (!$products) {
                return response()->json([
                    'message' => 'Failed to get Products'
                ], 402);
            }
            return response()->json([
                'message' => 'OK',
                'data' => $products
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
                'price' => 'required',
                'quantity' => 'required',
                'status' => 'required',
                'description' => 'required',
                'image' => 'required',
                'capital' => 'required|numeric|min:1'
            ]);
            $result = Product::create($validated);
            if (!$result) {
                return response()->json(['message'=>'Failed to add new Product'], 402);
            }
            return response()->json([
                'message' => 'Added new Product successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:products,id',
                'name' => 'required',
                'product_type' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'status' => 'required',
                'description' => 'required',
                'image' => 'required',
                'capital' => 'required|numeric|min:1'
            ]);
            $product = Product::find($validated['id']);
            $result = $product->update($validated);
            if (!$result) {
                return response()->json(['message'=>'Product update failed'], 402);
            }
            return response()->json(['message'=>'Product update completed'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function delete (Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:products,id'
            ]);
            $result = Product::find($validated['id'])->delete();
            if (!$result) {
                return response()->json(['message'=>'Failed to delete Product'], 402);
            }
            return response()->json(['message'=>'Product successfully deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function archive()
    {
        try {
            $data = Product::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
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
                'id' => 'required|exists:products,id'
            ]);
            $result = Product::onlyTrashed()->find($validated['id'])->restore();
            if (!$result) {
                return response()->json(['message'=>'Failed to restore Product'], 402);
            }
            return response()->json(['message'=>'Product restored successfully'], 200);
        } catch (Exception $e) {
            return response()->json([], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:products,id'
            ]);
            $result = Product::onlyTrashed()->find($validated['id'])->forceDelete();
            if (!$result) {
                return response()->json(['message'=>'Failed to delete Product'], 402);
            }
            return response()->json(['message'=>'Product has been permanently deleted'], 200);
        } catch (Exception $e) {

        }
    }
}
