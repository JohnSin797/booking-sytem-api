<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\RegistrationRequest;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::with('rating')->where('email', $validated['email'])->first();
            if(Hash::check($validated['password'], $user->password)) {
                $user->last_active = now();
                $user->save();
                return response()->json([
                    'message' => 'OK',
                    'data' => $user
                ], 200);
            }

            return response()->json([
                'message' => 'invalid email or password',
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email:rfc,dns',
                'password' => 'required|confirmed|min:8|max:15',
                'contact_number' => 'nullable',
                'address' => 'nullable'
            ]);
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address'],
                'last_active' => now()
            ]);
            if (!$user) {
                return response()->json(['message'=>'Failed to register account'], 402);
            }
            return response()->json([
                'message' => 'Registration success!',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function details()
    {
        try {
            $data = User::where('role', 'admin')->first();
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:user,id'
            ]);
            $data = User::find($validated['id']);
            if (!$data) {
                return response()->json(['message'=>'Can not find User'], 402);
            }
            return response()->json([
                'message' => 'OK',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
                'name' => 'required',
                'email' => 'required|email:rfc,dns',
                'password' => 'required',
                'new_password' => 'nullable',
                'password_confirmation' => 'nullable'
            ]);
            $user = User::find($validated['id']);
            if (!$validated['new_password'] && !Hash::check($validated['password'], $user->password)) {
                return response()->json(['message'=>'Incorrect password'], 402);
            } else if (!$validated['new_password'] && Hash::check($validated['password'], $user->password)) {
                $data = $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email']
                ]);
            } else if ($validated['new_password'] !== $validated['password_confirmation']) {
                return response()->json(['message'=>'Password did not match'], 403);
            } else {
                $data = $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['new_password'])
                ]);
            }
            return response()->json([
                'message'=>'User Profile successfully updated',
                'data'=>User::find($validated['id'])
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }

    public function information(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
                'contact_number' => 'nullable',
                'address' => 'nullable'
            ]);
            $user = User::find($validated['id']);
            $result = $user->update([
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address']
            ]);
            if (!$result) {
                return response()->json(['message'=>'Failed to update user information'], 402);
            }
            return response()->json([
                'message'=>'User information successfully updated',
                'data' => User::find($validated['id'])
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message'=>$e], 500);
        }
    }
}
