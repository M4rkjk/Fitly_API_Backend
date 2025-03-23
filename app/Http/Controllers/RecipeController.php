<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RecipeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::all()->map(function ($recipe) {
            if ($recipe->image_paths) {
                $recipe->image_paths = json_decode($recipe->image_paths, true);
            }
            return $recipe;
        });

        return response()->json($recipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:50',
            'ingredients' => 'required|max:500',
            'description' => 'required|max:2048',
            'avg_time' => 'required|max:50',
            'image_paths' => 'nullable|array',
            'image_paths.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image_paths')) {
            $imagePaths = [];

            foreach ($request->file('image_paths') as $image) {
                $imagePaths[] = $image->store('recipes', 'public');
            }
            $fields['image_paths'] = json_encode($imagePaths);
        }

        $recipe = $request->user()->recipes()->create($fields);

        return response()->json([
            'message' => 'Recipe created successfully!',
            'data' => $recipe
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        if ($recipe->image_paths) {
            $recipe->image_paths = json_decode($recipe->image_paths, true);
        }

        return response()->json([
            'recipe' => $recipe
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        if (auth()->user()->id !== $recipe->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Töröljük a képeket a fájlrendszerből
        if ($recipe->image_paths) {
            $imagePaths = json_decode($recipe->image_paths, true);
            foreach ($imagePaths as $path) {
                \Storage::disk('public')->delete($path);
            }
        }

        // Töröljük a receptet
        $recipe->delete();

        return response()->json([
            'message' => 'Recipe deleted successfully!'
        ]);
    }
}
