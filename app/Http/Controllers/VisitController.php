<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        if($user === null){
            abort(500);
        }
        $role = $user->role;

        // Manager is allowed to retrieve all visits
        $visits = Visit::joinCustomer()->retrieveInfo();
        if($role === 1){
            $visits = $visits->get();
        }else{
            $visits = $visits->whereUserId($user->id)->get();
        }
        // Returns in JSON format and include application/json to content-type
        return response()->json($visits->toJson());
    }
}
