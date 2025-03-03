<?php

namespace App\Http\Controllers;

use App\Models\PostReactions;
use Illuminate\Http\Request;

class PostReactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reactions = PostReactions::all();

        return response()->json($reactions);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'reaction' => 'required|string|in:like,dislike,love',
        ]);

        $reaction = PostReactions::create($validated);

        return response()->json($reaction, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(PostReactions $postReactions)
    {
        return response()->json($postReactions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostReactions $postReactions)
    {
        $validated = $request->validate([
            'reaction' => 'required|string|in:like,dislike,love',
        ]);

        $postReactions->update($validated);

        return response()->json($postReactions);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostReactions $postReactions)
    {
        $postReactions->delete();

        return response()->json(['message' => "Reaction is deleted"]);
    }
}
