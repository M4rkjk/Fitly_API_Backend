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

    public function getUserTotalCalories($userId)
    {
        $user = User::findOrFail($userId);
        $totalCalories = 0;

        foreach ($user->meals as $meal) {
            $mealCalories = ($meal->kcal * $meal->pivot->amount) / 100;
            $totalCalories += $mealCalories;
        }

        return response()->json(['total_calories' => $totalCalories]);
    }

    public function removeMealFromUser(Request $request, $mealId)
    {
        $user = auth()->user();

        if (!$user->meals()->where('meal_id', $mealId)->exists()) {
            return response()->json(['message' => 'Ez az étel nincs a listádon.'], 404);
        }

        $user->meals()->detach($mealId);

        return response()->json(['message' => 'Étel eltávolítva a listádról.']);
    }
}
