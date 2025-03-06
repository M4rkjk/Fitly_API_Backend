<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostReactionController extends Controller implements HasMiddleware
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
        $reactions = PostReaction::all();

        return response()->json($reactions);
    }


    public function storeReaction(Request $request, Post $post)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reaction' => 'required|in:like,dislike,love',
        ]);

        $reaction = PostReaction::updateOrCreate(
            ['post_id' => $post->id, 'user_id' => $validated['user_id']],
            ['reaction' => $validated['reaction']]
        );

        return response()->json($reaction, 200);
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
        if ($postReaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You are not authorized to update this reaction.'], 403);
        }

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
        if ($postReaction->user_id !== request()->user()->id) {
            return response()->json(['message' => 'You are not authorized to delete this reaction.'], 403);
        }

        $postReaction->delete();

        return response()->json(['message' => "Reaction is deleted"]);
    }
}
