<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Product;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        try {
            $data = Booking::with('user', 'package.packageItem.product')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'message' => 'OK',
                'data' => $data,
                'user' => User::where('role', 'user')->count()
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function customize(Request $request) 
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'product_type' => 'required',
                'quantity' => 'required',
                'total_price' => 'required',
                'product_id' => 'required|array|exists:products,id',
                'location' => 'required',
                'schedule' => 'required',
                'message' => 'nullable',
                'user_id' => 'required|exists:users,id',
                'services' => 'required'
            ]);
            $totalCapital = Product::whereIn('id', $validated['product_id'])->sum('capital');
            $package = Package::create([
                'name' => $validated['name'],
                'package_type' => 'customized',
                'product_type' => $validated['product_type'],
                'quantity' => $validated['quantity'],
                'status' => 'active',
                'total_price' => doubleval($validated['total_price']),
                'capital' => $totalCapital,
            ]);
            if (!$package) {
                return response()->json(['message'=>'Failed to create Package'], 402);
            }
            foreach ($validated['product_id'] as $key => $value) {
                PackageItem::create([
                    'product_id' => $value,
                    'package_id' => $package->id,
                ]);
                $product = Product::find($value);
                $quantity = intval($product->quantity) - 1;
                $product->update([
                    'quantity' => $quantity
                ]);
            }
            $uniqueId = BookingController::generateOfficialReceiptNumber();
            $result = Booking::create([
                'package_id' => $package->id,
                'user_id' => $validated['user_id'],
                'order_date' => $validated['schedule'],
                'location' => $validated['location'],
                'message' => $validated['message'],
                'quantity' => $validated['quantity'],
                'transaction_number' => $uniqueId,
                'services' => $validated['services']
            ]);
            if (!$result) {
                return response()->json(['message'=>'Failed to book package'], 403);
            }
            return response()->json(['message'=>'Customized Package successfully booked', 'data'=>$validated['total_price']], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:bookings,id',
                'status' => 'required'
            ]);
            $book = Booking::find($validated['id']);
            $result = $book->update([
                'status' => $validated['status']
            ]);
            if (!$result) {
                return response()->json(['message'=>'Failed to update Booking'], 402);
            }
            $pack = Package::find($book->package_id);
            $qty = intval($pack->quantity) - intval($book->quantity);
            $pack->update([
                'quantity' => $qty
            ]);
            return response()->json(['message'=>'Booking successfully updated'], 200);
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
            $data = Booking::with('package.packageItem.product', 'package.rating', 'package.packageFeedbacks.user.packageRating', 'package.packageFeedbacks.packageFeedbackReply.user.packageRating')->where('user_id', $validated['user_id'])->get();
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
                'message' => 'nullable',
                'location' => 'required',
                'date' => 'required',
                'quantity' => 'required|min:1',
                'services' => 'required'
            ]);
            $uniqueId = BookingController::generateOfficialReceiptNumber();
            $result = Booking::create([
                'package_id' => $validated['package_id'],
                'user_id' => $validated['user_id'],
                'message' => $validated['message'],
                'order_date' => $validated['date'],
                'location' => $validated['location'],
                'transaction_number' => $uniqueId,
                'services' => $validated['services']
            ]);
            if (!$result) {
                return response()->json(['message'=>'Failed to book'], 402);
            }
            return response()->json(['message'=>'Booked successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    private static function generateOfficialReceiptNumber() {
        $currentDate = Carbon::now();
        $monthNumber = $currentDate->format('m'); 
        $yearNumber = $currentDate->format('Y'); 
    
        $randomNumber = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
    
        $officialReceiptNumber = sprintf('%02d-%05d-%02d', $monthNumber, $randomNumber, $yearNumber);
    
        return $officialReceiptNumber;
    }
}
