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
            return array_merge($recipe->toArray(), [
                'image_urls' => $recipe->image_urls
            ]);
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
        $recipe->load('user');

        return response()->json([
            'recipe' => array_merge($recipe->toArray(), [
                'image_urls' => $recipe->image_urls
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        if ($request->user()->id !== $recipe->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $fields = $request->validate([
            'title' => 'nullable|max:50',
            'ingredients' => 'nullable|max:500',
            'description' => 'nullable|max:2048',
            'avg_time' => 'nullable|max:50',
            'image_paths' => 'nullable|array',
            'image_paths.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($request->hasFile('image_paths')) {
            $imagePaths = [];

            foreach ($request->file('image_paths') as $image) {
                $imagePaths[] = $image->store('recipes', 'public');
            }

            $fields['image_paths'] = json_encode($imagePaths);
        }

        $recipe->update($fields);

        return response()->json([
            'message' => 'Recipe updated successfully!',
            'data' => $recipe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        if (auth()->user()->id !== $recipe->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
