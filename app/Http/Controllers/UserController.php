<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Model binding, retrieves id & name of specific user
    public function retrieveUser(User $user){
        return response()->json([
            "id" => $user->id,
            "name" => $user->name
        ]);
    }
}
