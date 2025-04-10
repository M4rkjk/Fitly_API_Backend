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

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kcal' => 'required|numeric|min:0|max:1200',
            'fat' => 'nullable|numeric|min:0|max:150',
            'carb' => 'nullable|numeric|min:0|max:150',
            'protein' => 'nullable|numeric|min:0|max:150',
            'salt' => 'nullable|numeric|min:0|max:100',
            'sugar' => 'nullable|numeric|min:0|max:100',
        ]);

        $meal = new Meal($validated);
        $meal->save();

        return response()->json([
            'message' => 'Új étel sikeresen létrehozva.',
            'meal' => $meal,
        ], 201);
    }

    public function addMealToUser(Request $request)
    {
        $user = auth()->user();
        $meal = Meal::findOrFail($request->meal_id);

        $caloriesToSubtract = ($meal->kcal * $request->amount) / 100;

        $user->meals()->attach($meal->id, [
            'amount' => $request->amount,
            'created_at' => now(),
        ]);

        if (!is_null($user->recommended_calories)) {
            $user->recommended_calories -= $caloriesToSubtract;

            if ($user->recommended_calories < 0) {
                $user->recommended_calories = 0;
            }
            $user->save();
        }

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
