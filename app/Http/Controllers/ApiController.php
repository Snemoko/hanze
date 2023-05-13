<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function issueToken(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::WhereEmail($request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            "token" => $user->createToken($request->device_name)->plainTextToken
        ]);
    }

    public function retrieveList(){
        // Returns a list of visits with all the related customer and user information
        return response()->json([
            "visits" => Visit::with(['customer', 'user'])->get()
        ]);
    }
}
