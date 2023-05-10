<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function index(){
        $user = Auth::user();
        if($user === null){
            abort(403);
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

    // Model binding, retrieves specific visit but restricted access if user is not the manager
    public function retrieveVisit(Visit $visit){
        if(Auth::user()->role !== 1 && $visit->user_id !== Auth::user()->id){
            abort(500);
        }
        return response()->json($visit->toJson());
    }

    // Model binding, retrieves specific visit but restricted access if user is not the manager
    public function update(Visit $visit, Request $request){
        $request->validate([
            "user_id" => ["nullable", "exists:users,id"], //check if user exists in db
            "customer_id" => ["required", "exists:customers,id"], // check if customer exists in db
            "report" => ["required"],
            "appointment_date" => ["nullable"],
            "appointment_time" => ["nullable"],
        ]);

        $user = Auth::user();
        if($user === null){
            abort(500);
        }

        $visit->user_id = $request->user_id;
        $visit->customer_id = $request->customer_id;
        $visit->report = $request->report;
        $visit->appointment_date = $request->appointment_date;
        $visit->appointment_time = $request->appointment_time;


        // Check if user id is changed & logged in user is not manager
        if($visit->isDirty('user_id') && $user->role !== 1){
            abort(500);
        }
        $visit->save();

        return response()->json([
            "success", "succesfully changed the visit with ID " . $visit->id,
        ]);
    }
}
