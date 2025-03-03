<?php

namespace App\Http\Controllers;

use App\Models\PostReaction;
use Illuminate\Http\Request;

class PostReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reactions = PostReaction::all();

        return response()->json($reactions);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'reaction' => 'required|string|in:like,dislike,love',
        ]);

        $reaction = PostReaction::create($validated);

        return response()->json($reaction, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(PostReaction $postReaction)
    {
        return response()->json($postReaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostReaction $postReaction)
    {
        $validated = $request->validate([
            'reaction' => 'required|string|in:like,dislike,love',
        ]);

        $postReaction->update([
            'reaction' => $validated['reaction']
        ]);

        return response()->json($postReaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostReaction $postReaction)
    {
        $postReaction->delete();

        return response()->json(['message' => "Reaction is deleted"]);
    }
}
