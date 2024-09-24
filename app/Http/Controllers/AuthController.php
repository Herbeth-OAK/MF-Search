<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $imageBinary = null;
        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $image =  $manager->read($request->file('image'));
            
            $image->scale(500, 500);
            
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            $image->save($tempPath, 75, 'jpg');
            
            $imageBinary = base64_encode(file_get_contents($tempPath));
            
            unlink($tempPath);
        }
    
        $user = User::create([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $imageBinary,
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;


        if (is_resource($user->image)) {
            $image = (stream_get_contents($user->image));
        } else {
            $image = $user->image;
        }

        Log::info($image);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id,
            'image' => $image,
        ]);
    }
}
