<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::all();
        return response()->json($ingredients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kcal' => 'required|numeric',
            'fat' => 'required|numeric',
            'carb' => 'required|numeric',
            'protein' => 'required|numeric',
            'salt' => 'required|numeric',
            'sugar' => 'required|numeric',
            'meal_id' => 'nullable|exists:meals,id',
            'amount' => 'nullable|numeric|min:0.1',
        ]);

        $ingredient = Ingredient::create($request->only(['name', 'kcal', 'fat', 'carb', 'protein', 'salt', 'sugar']));

        if ($request->has('meal_id')) {
            $ingredient->meals()->attach($request->meal_id, [
                'user_id' => auth()->id(),
                'amount' => $request->amount ?? 0 
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ingredient successfully created!',
            'data' => $ingredient->load('meals')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
