<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meals = Meal::all();
        return response()->json($meals);
    }

    public function addMealToUser(Request $request)
    {
        $user = auth()->user(); 
        $meal = Meal::findOrFail($request->meal_id);

        $user->meals()->attach($meal->id, ['amount' => $request->amount]);

        return response()->json(['message' => 'Étel hozzáadva a felhasználóhoz.']);
    }
}
